<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceSheetReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'as_of_date' => ['nullable', 'date'],
        ]);
        $branchId = $filters['branch_id'] ?? null;
        $asOfDate = $filters['as_of_date'] ?? now()->toDateString();

        $branches = Branch::where('is_active', true)->get();
        $selectedBranch = $branchId ? Branch::find($branchId) : null;

        // 1. Ambil Akumulasi Saldo Akun Aset, Kewajiban, dan Ekuitas per Tanggal Neraca
        $accountBalances = JournalItem::select(
            'chart_of_accounts.id',
            'chart_of_accounts.code',
            'chart_of_accounts.name',
            'chart_of_accounts.type',
            'chart_of_accounts.normal_balance',
            DB::raw('SUM(journal_items.debit) as total_debit'),
            DB::raw('SUM(journal_items.credit) as total_credit')
        )
            ->join('chart_of_accounts', 'journal_items.account_id', '=', 'chart_of_accounts.id')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereIn('chart_of_accounts.type', ['asset', 'liability', 'equity'])
            ->where('journal_entries.date', '<=', $asOfDate)
            ->where('journal_entries.status', 'posted')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('journal_entries.branch_id', $branchId);
            })
            ->groupBy(
                'chart_of_accounts.id',
                'chart_of_accounts.code',
                'chart_of_accounts.name',
                'chart_of_accounts.type',
                'chart_of_accounts.normal_balance'
            )
            ->orderBy('chart_of_accounts.code')
            ->get();

        // 2. Hitung Laba Tahun Berjalan (Net Income YTD) dari Akun Revenue & Expense
        $incomeStatementItems = JournalItem::select(
            'chart_of_accounts.type',
            'chart_of_accounts.normal_balance',
            DB::raw('SUM(journal_items.debit) as total_debit'),
            DB::raw('SUM(journal_items.credit) as total_credit')
        )
            ->join('chart_of_accounts', 'journal_items.account_id', '=', 'chart_of_accounts.id')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereIn('chart_of_accounts.type', ['revenue', 'expense'])
            ->where('journal_entries.date', '<=', $asOfDate)
            ->where('journal_entries.status', 'posted')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('journal_entries.branch_id', $branchId);
            })
            ->groupBy('chart_of_accounts.type', 'chart_of_accounts.normal_balance')
            ->get();

        $currentYearEarnings = 0;
        foreach ($incomeStatementItems as $item) {
            if ($item->type === 'revenue') {
                $currentYearEarnings += ($item->total_credit - $item->total_debit);
            } else { // expense
                $currentYearEarnings -= ($item->total_debit - $item->total_credit);
            }
        }

        // 3. Kelompokkan Akun ke Aset, Kewajiban, dan Ekuitas
        $assets = collect();
        $liabilities = collect();
        $equities = collect();

        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquities = 0;

        foreach ($accountBalances as $item) {
            $netAmount = ($item->normal_balance === 'debit')
                ? ($item->total_debit - $item->total_credit)
                : ($item->total_credit - $item->total_debit);

            if ($netAmount == 0) {
                continue;
            }

            $data = (object) [
                'code' => $item->code,
                'name' => $item->name,
                'amount' => $netAmount,
            ];

            if ($item->type === 'asset') {
                $assets->push($data);
                $totalAssets += $netAmount;
            } elseif ($item->type === 'liability') {
                $liabilities->push($data);
                $totalLiabilities += $netAmount;
            } else { // equity
                $equities->push($data);
                $totalEquities += $netAmount;
            }
        }

        // Tambahkan Laba Tahun Berjalan ke komponen Ekuitas
        if ($currentYearEarnings != 0) {
            $equities->push((object) [
                'code' => '3-3001',
                'name' => 'Laba Tahun Berjalan (Current Year Earnings)',
                'amount' => $currentYearEarnings,
            ]);
            $totalEquities += $currentYearEarnings;
        }

        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquities;
        $isBalanced = round($totalAssets, 2) === round($totalLiabilitiesAndEquity, 2);

        return view('reports.balance-sheet', compact(
            'branches',
            'branchId',
            'selectedBranch',
            'asOfDate',
            'assets',
            'liabilities',
            'equities',
            'totalAssets',
            'totalLiabilities',
            'totalEquities',
            'totalLiabilitiesAndEquity',
            'isBalanced'
        ));
    }
}
