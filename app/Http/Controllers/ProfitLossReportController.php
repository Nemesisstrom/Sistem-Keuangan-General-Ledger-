<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfitLossReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
        $branchId = $filters['branch_id'] ?? null;
        $startDate = $filters['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $filters['end_date'] ?? now()->endOfMonth()->toDateString();

        $branches = Branch::where('is_active', true)->get();
        $selectedBranch = $branchId ? Branch::find($branchId) : null;

        // Ambil akumulasi saldo per Akun COA tipe Revenue dan Expense pada periode tanggal
        $accountBalances = JournalItem::select(
            'chart_of_accounts.id',
            'chart_of_accounts.code',
            'chart_of_accounts.name',
            'chart_of_accounts.type',
            'chart_of_accounts.normal_balance',
            'chart_of_accounts.parent_id',
            DB::raw('SUM(journal_items.debit) as total_debit'),
            DB::raw('SUM(journal_items.credit) as total_credit')
        )
            ->join('chart_of_accounts', 'journal_items.account_id', '=', 'chart_of_accounts.id')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereIn('chart_of_accounts.type', ['revenue', 'expense'])
            ->whereBetween('journal_entries.date', [$startDate, $endDate])
            ->where('journal_entries.status', 'posted')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('journal_entries.branch_id', $branchId);
            })
            ->groupBy(
                'chart_of_accounts.id',
                'chart_of_accounts.code',
                'chart_of_accounts.name',
                'chart_of_accounts.type',
                'chart_of_accounts.normal_balance',
                'chart_of_accounts.parent_id'
            )
            ->get();

        // Mengelompokkan berdasarkan tipe akun & menghitung saldo bersih
        $revenues = collect();
        $expenses = collect();

        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($accountBalances as $item) {
            // Hitung net balance sesuai saldo normal
            if ($item->normal_balance === 'credit') {
                $netAmount = $item->total_credit - $item->total_debit;
            } else {
                $netAmount = $item->total_debit - $item->total_credit;
            }

            $accountData = (object) [
                'code' => $item->code,
                'name' => $item->name,
                'amount' => $netAmount,
            ];

            if ($item->type === 'revenue') {
                $revenues->push($accountData);
                $totalRevenue += $netAmount;
            } else {
                $expenses->push($accountData);
                $totalExpense += $netAmount;
            }
        }

        $netProfit = $totalRevenue - $totalExpense;

        return view('reports.profit-loss', compact(
            'branches',
            'branchId',
            'selectedBranch',
            'startDate',
            'endDate',
            'revenues',
            'expenses',
            'totalRevenue',
            'totalExpense',
            'netProfit'
        ));
    }
}
