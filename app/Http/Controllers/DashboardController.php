<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalItem;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 10 transaksi jurnal entri terbaru (pastikan eager loading relasi aman)
        $recentJournals = Journal::with(['items.account'])
            ->latest('date')
            ->take(10)
            ->get();

        // 2. Kalkulasi Ringkasan Keuangan Ringkas
        $accountBalances = JournalItem::query()
            ->join('chart_of_accounts', 'journal_items.account_id', '=', 'chart_of_accounts.id')
            ->whereHas('journal', fn ($query) => $query->where('status', 'posted'))
            ->selectRaw(
                'chart_of_accounts.type, chart_of_accounts.code, '.
                'SUM(journal_items.debit) as debit_total, SUM(journal_items.credit) as credit_total'
            )
            ->groupBy('chart_of_accounts.type', 'chart_of_accounts.code')
            ->get();

        $totalCash = $accountBalances
            ->filter(fn ($account) => $account->type === 'asset' && str_starts_with($account->code, '11'))
            ->sum(fn ($account) => (float) $account->debit_total - (float) $account->credit_total);

        $totalRevenue = $accountBalances
            ->where('type', 'revenue')
            ->sum(fn ($account) => (float) $account->credit_total - (float) $account->debit_total);

        $totalExpense = $accountBalances
            ->where('type', 'expense')
            ->sum(fn ($account) => (float) $account->debit_total - (float) $account->credit_total);

        $netProfit = $totalRevenue - $totalExpense;

        return view('dashboard.index', compact(
            'recentJournals',
            'totalCash',
            'totalRevenue',
            'totalExpense',
            'netProfit'
        ));
    }
}
