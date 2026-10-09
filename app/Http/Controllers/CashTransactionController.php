<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashTransactionController extends Controller
{
    public function createIn()
    {
        $cashAccounts = ChartOfAccount::where('type', 'asset')->get();
        $revenueAccounts = ChartOfAccount::whereIn('type', ['revenue', 'equity'])->get();
        $branches = Branch::all(); // Untuk opsi pilihan cabang bagi Admin

        return view('cash.in', compact('cashAccounts', 'revenueAccounts', 'branches'));
    }

    public function storeIn(Request $request)
    {
        $validated = $request->validate([
            'transaction_date'  => 'required|date',
            'cash_account_id'   => 'required|exists:chart_of_accounts,id',
            'source_account_id' => 'required|exists:chart_of_accounts,id',
            'amount'            => 'required|numeric|min:1',
            'description'       => 'required|string|max:255',
            'branch_id'         => 'nullable|exists:branches,id',
        ]);

        // Jika Staff, gunakan branch_id milik user login. Jika Admin, gunakan inputan form (atau fallback ke user branch)
        $branchId = auth()->user()->branch_id ?? $request->input('branch_id');

        DB::transaction(function () use ($validated, $branchId) {
            $journal = Journal::create([
                'branch_id'        => $branchId,
                'created_by'       => auth()->id(),
                'transaction_date' => $validated['transaction_date'],
                'description'      => $validated['description'],
                'status'           => 'posted',
            ]);

            // Debit: Kas / Bank
            $journal->items()->create([
                'chart_of_account_id' => $validated['cash_account_id'],
                'debit'               => $validated['amount'],
                'credit'              => 0,
            ]);

            // Kredit: Pendapatan / Sumber
            $journal->items()->create([
                'chart_of_account_id' => $validated['source_account_id'],
                'debit'               => 0,
                'credit'              => $validated['amount'],
            ]);
        });

        return redirect()->route('journals.index')->with('success', 'Pemasukan kas berhasil dicatat.');
    }

    public function createOut()
    {
        $expenseAccounts = ChartOfAccount::where('type', 'expense')->get();
        $cashAccounts = ChartOfAccount::where('type', 'asset')->get();
        $branches = Branch::all();

        return view('cash.out', compact('expenseAccounts', 'cashAccounts', 'branches'));
    }

    public function storeOut(Request $request)
    {
        $validated = $request->validate([
            'transaction_date'   => 'required|date',
            'expense_account_id' => 'required|exists:chart_of_accounts,id',
            'cash_account_id'    => 'required|exists:chart_of_accounts,id',
            'amount'             => 'required|numeric|min:1',
            'description'        => 'required|string|max:255',
            'branch_id'         => 'nullable|exists:branches,id',
        ]);

        $branchId = auth()->user()->branch_id ?? $request->input('branch_id');

        DB::transaction(function () use ($validated, $branchId) {
            $journal = Journal::create([
                'branch_id'        => $branchId,
                'created_by'       => auth()->id(),
                'transaction_date' => $validated['transaction_date'],
                'description'      => $validated['description'],
                'status'           => 'posted',
            ]);

            // Debit: Beban / Operasional
            $journal->items()->create([
                'chart_of_account_id' => $validated['expense_account_id'],
                'debit'               => $validated['amount'],
                'credit'              => 0,
            ]);

            // Kredit: Kas / Bank
            $journal->items()->create([
                'chart_of_account_id' => $validated['cash_account_id'],
                'debit'               => 0,
                'credit'              => $validated['amount'],
            ]);
        });

        return redirect()->route('journals.index')->with('success', 'Pengeluaran kas berhasil dicatat.');
    }
}
