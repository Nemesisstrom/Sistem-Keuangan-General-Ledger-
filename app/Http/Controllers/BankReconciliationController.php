<?php

namespace App\Http\Controllers;

use App\Models\BankReconciliation;
use App\Models\ChartOfAccount;
use App\Models\JournalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar akun yang biasanya bertipe aset (KAS/BANK)
        $bankAccounts = ChartOfAccount::where('type', 'asset')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $reconciliations = BankReconciliation::with(['account', 'creator'])
            ->latest('statement_date')
            ->paginate(10);

        // Jika user memilih akun untuk dihitung saldonya secara real-time
        $selectedAccount = null;
        $calculatedBookBalance = 0;

        if ($request->filled('account_id')) {
            $selectedAccount = ChartOfAccount::findOrFail($request->account_id);

            // Hitung akumulasi saldo buku dari journal items
            $totals = JournalItem::select(
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit')
            )
                ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
                ->where('journal_items.account_id', $selectedAccount->id)
                ->where('journal_entries.status', 'posted')
                ->when($request->filled('statement_date'), function ($q) use ($request) {
                    $q->where('journal_entries.date', '<=', $request->statement_date);
                })
                ->first();

            if ($selectedAccount->normal_balance === 'debit') {
                $calculatedBookBalance = ($totals->total_debit ?? 0) - ($totals->total_credit ?? 0);
            } else {
                $calculatedBookBalance = ($totals->total_credit ?? 0) - ($totals->total_debit ?? 0);
            }
        }

        return view('reconciliations.index', compact(
            'bankAccounts',
            'reconciliations',
            'selectedAccount',
            'calculatedBookBalance'
        ));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'account_id' => ['required', 'exists:chart_of_accounts,id'],
            'statement_date' => ['required', 'date'],
            'book_balance' => ['required', 'numeric'],
            'bank_statement_balance' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        // Hitung selisih
        $difference = $validated['bank_statement_balance'] - $validated['book_balance'];

        BankReconciliation::create([
            'account_id' => $validated['account_id'],
            'statement_date' => $validated['statement_date'],
            'book_balance' => $validated['book_balance'],
            'bank_statement_balance' => $validated['bank_statement_balance'],
            'difference' => $difference,
            'notes' => $validated['notes'],
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('reconciliations.index')
            ->with('success', 'Rekonsiliasi bank berhasil disimpan.');
    }
}
