<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\JournalItem;
use Illuminate\Http\Request;

class GeneralLedgerReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'account_id' => ['nullable', 'exists:chart_of_accounts,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
        $branchId = $filters['branch_id'] ?? null;
        $accountId = $filters['account_id'] ?? null;
        $startDate = $filters['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $filters['end_date'] ?? now()->endOfMonth()->toDateString();

        $branches = Branch::where('is_active', true)->get();
        $accounts = ChartOfAccount::where('is_active', true)
            ->orderBy('code')
            ->get();

        $selectedAccount = null;
        $openingBalance = 0;
        $journalItems = collect();

        if ($accountId) {
            $selectedAccount = ChartOfAccount::findOrFail($accountId);

            // 1. Hitung Saldo Awal (Transaksi sebelum start_date)
            $previousQuery = JournalItem::whereHas('journalEntry', function ($q) use ($branchId, $startDate) {
                $q->where('date', '<', $startDate)
                    ->where('status', 'posted');
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            })->where('account_id', $accountId);

            $prevDebit = (float) $previousQuery->sum('debit');
            $prevCredit = (float) $previousQuery->sum('credit');

            // Sesuaikan rumus saldo awal berdasarkan Saldo Normal Akun
            if ($selectedAccount->normal_balance === 'debit') {
                $openingBalance = $prevDebit - $prevCredit;
            } else {
                $openingBalance = $prevCredit - $prevDebit;
            }

            // 2. Ambil Transaksi Mutasi pada Periode Terpilih
            $journalItems = JournalItem::with(['journalEntry.branch', 'journalEntry'])
                ->whereHas('journalEntry', function ($q) use ($branchId, $startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate])
                        ->where('status', 'posted');
                    if ($branchId) {
                        $q->where('branch_id', $branchId);
                    }
                })
                ->where('account_id', $accountId)
                ->get()
                ->sortBy(function ($item) {
                    return $item->journalEntry->date.'-'.$item->journalEntry->id;
                });
        }

        return view('reports.general-ledger', compact(
            'branches',
            'accounts',
            'branchId',
            'accountId',
            'startDate',
            'endDate',
            'selectedAccount',
            'openingBalance',
            'journalItems'
        ));
    }
}
