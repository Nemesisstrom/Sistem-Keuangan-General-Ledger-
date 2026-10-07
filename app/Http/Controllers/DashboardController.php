<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 10 transaksi jurnal entri terbaru (pastikan eager loading relasi aman)
        $recentJournals = Journal::with(['items.account'])
            ->latest('transaction_date')
            ->take(10)
            ->get();

        // 2. Kalkulasi Ringkasan Keuangan Ringkas
        $totalCash = ChartOfAccount::where('account_type', 'Asset')
            ->where('account_code', 'like', '11%')
            ->sum('balance') ?? 0;

        $totalRevenue = ChartOfAccount::where('account_type', 'Revenue')
            ->sum('balance') ?? 0;

        $totalExpense = ChartOfAccount::where('account_type', 'Expense')
            ->sum('balance') ?? 0;

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
