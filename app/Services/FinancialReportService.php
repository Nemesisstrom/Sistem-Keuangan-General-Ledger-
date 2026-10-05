<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalItem;

class FinancialReportService
{
    /**
     * Menghitung Neraca Saldo (Trial Balance) untuk rentang tanggal tertentu.
     *
     * @param string|null $startDate (format: Y-m-d)
     * @param string|null $endDate (format: Y-m-d)
     * @param int|null $branchId
     * @return array
     */
    public function getTrialBalance(?string $startDate = null, ?string $endDate = null, ?int $branchId = null): array
    {
        // 1. Ambil agregasi total debit & kredit per akun dari jurnal yang aktif (posted)
        $balances = JournalItem::query()
            ->selectRaw('account_id, SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->whereHas('journal', function ($q) use ($startDate, $endDate, $branchId) {
                $q->where('status', 'posted')
                    ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
                    ->when($startDate, fn($query) => $query->whereDate('transaction_date', '>=', $startDate))
                    ->when($endDate, fn($query) => $query->whereDate('transaction_date', '<=', $endDate));
            })
            ->groupBy('account_id')
            ->get()
            ->keyBy('account_id');

        // 2. Ambil seluruh master data akun (Chart of Accounts)
        $accounts = ChartOfAccount::orderBy('code', 'asc')->get();

        $trialBalanceItems = [];
        $grandTotalDebit = 0.0;
        $grandTotalCredit = 0.0;

        foreach ($accounts as $account) {
            $item = $balances->get($account->id);

            $debit = $item ? (float) $item->total_debit : 0.0;
            $credit = $item ? (float) $item->total_credit : 0.0;

            // Abaikan akun yang tidak memiliki mutasi transaksi di periode ini
            if ($debit == 0 && $credit == 0) {
                continue;
            }

            // Hitung nilai saldo bersih (net_balance) sesuai posisi normal akun
            $normalBalance = strtolower($account->normal_balance ?? 'debit');
            $netBalance = ($normalBalance === 'debit')
                ? $debit - $credit
                : $credit - $debit;

            $trialBalanceItems[] = [
                'account_id'     => $account->id,
                'account_code'   => $account->code,
                'account_name'   => $account->name,
                'normal_balance' => $account->normal_balance,
                'total_debit'    => $debit,
                'total_credit'   => $credit,
                'net_balance'    => $netBalance,
            ];

            $grandTotalDebit += $debit;
            $grandTotalCredit += $credit;
        }

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'items'              => $trialBalanceItems,
            'grand_total_debit'  => $grandTotalDebit,
            'grand_total_credit' => $grandTotalCredit,
            'is_balanced'        => abs($grandTotalDebit - $grandTotalCredit) < 0.0001,
        ];
    }

    /**
     * Menghitung Laporan Laba Rugi (Income Statement / Profit & Loss)
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @param int|null $branchId
     * @return array
     */
    public function getIncomeStatement(?string $startDate = null, ?string $endDate = null, ?int $branchId = null): array
    {
        // Dapatkan Neraca Saldo terlebih dahulu
        $trialBalance = $this->getTrialBalance($startDate, $endDate, $branchId);

        $revenues = [];
        $expenses = [];

        $totalRevenue = 0.0;
        $totalExpense = 0.0;

        foreach ($trialBalance['items'] as $item) {
            $account = ChartOfAccount::find($item['account_id']);
            if (!$account) {
                continue;
            }

            $type = strtolower($account->type); // e.g., 'revenue', 'income', 'expense', 'cost_of_goods_sold'

            // --- Kategori Pendapatan / Penjualan ---
            if (in_array($type, ['revenue', 'income', 'sales'])) {
                $amount = $item['total_credit'] - $item['total_debit'];
                $revenues[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'amount'       => $amount,
                ];
                $totalRevenue += $amount;
            }
            // --- Kategori Beban / HPP ---
            elseif (in_array($type, ['expense', 'cost_of_goods_sold', 'operational_expense', 'other_expense'])) {
                $amount = $item['total_debit'] - $item['total_credit'];
                $expenses[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'amount'       => $amount,
                ];
                $totalExpense += $amount;
            }
        }

        $netProfit = $totalRevenue - $totalExpense;

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
            'revenues'      => $revenues,
            'total_revenue' => $totalRevenue,
            'expenses'      => $expenses,
            'total_expense' => $totalExpense,
            'net_profit'    => $netProfit,
            'status'        => $netProfit >= 0 ? 'PROFIT' : 'LOSS',
        ];
    }

    /**
     * Menghitung Laporan Neraca Keuangan (Balance Sheet)
     * Persamaan Akuntansi: ASET = LIABILITAS + EKUITAS
     *
     * @param string|null $endDate (sampai tanggal tertentu)
     * @param int|null $branchId
     * @return array
     */
    public function getBalanceSheet(?string $endDate = null, ?int $branchId = null): array
    {
        // Neraca akumulasi ditarik hingga tanggal penutupan ($endDate)
        $trialBalance = $this->getTrialBalance(null, $endDate, $branchId);

        $assets = [];
        $liabilities = [];
        $equity = [];

        $totalAssets = 0.0;
        $totalLiabilities = 0.0;
        $totalEquity = 0.0;

        foreach ($trialBalance['items'] as $item) {
            $account = ChartOfAccount::find($item['account_id']);
            if (!$account) {
                continue;
            }

            $type = strtolower($account->type); // e.g., 'asset', 'liability', 'equity'

            // --- ASET (Aktiva) ---
            if (in_array($type, ['asset', 'current_asset', 'fixed_asset'])) {
                $amount = $item['total_debit'] - $item['total_credit'];
                $assets[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'amount'       => $amount,
                ];
                $totalAssets += $amount;
            }
            // --- LIABILITAS / KEWAJIBAN (Pasiva) ---
            elseif (in_array($type, ['liability', 'current_liability', 'long_term_liability'])) {
                $amount = $item['total_credit'] - $item['total_debit'];
                $liabilities[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'amount'       => $amount,
                ];
                $totalLiabilities += $amount;
            }
            // --- EKUITAS / MODAL (Pasiva) ---
            elseif (in_array($type, ['equity', 'retained_earnings'])) {
                $amount = $item['total_credit'] - $item['total_debit'];
                $equity[] = [
                    'account_code' => $account->code,
                    'account_name' => $account->name,
                    'amount'       => $amount,
                ];
                $totalEquity += $amount;
            }
        }

        // Hitung Laba/Rugi Berjalan Periode Ini (Current Earnings) untuk melengkapi Modal Ekuitas
        $incomeStatement = $this->getIncomeStatement(null, $endDate, $branchId);
        $currentEarnings = $incomeStatement['net_profit'];

        if ($currentEarnings != 0) {
            $equity[] = [
                'account_code' => 'NET_PROFIT',
                'account_name' => 'Laba / (Rugi) Berjalan',
                'amount'       => $currentEarnings,
            ];
            $totalEquity += $currentEarnings;
        }

        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquity;

        return [
            'as_of_date'                   => $endDate ?? now()->format('Y-m-d'),
            'assets'                       => $assets,
            'total_assets'                 => $totalAssets,
            'liabilities'                  => $liabilities,
            'total_liabilities'            => $totalLiabilities,
            'equity'                       => $equity,
            'total_equity'                 => $totalEquity,
            'total_liabilities_and_equity' => $totalLiabilitiesAndEquity,
            'is_balanced'                  => abs($totalAssets - $totalLiabilitiesAndEquity) < 0.0001,
        ];
    }
}
