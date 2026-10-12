<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalItem;
use App\Models\ChartOfAccount;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar jurnal yang dikategorikan sebagai penyesuaian (berdasarkan referensi atau deskripsi)
        $adjustments = Journal::with(['branch', 'creator', 'items.account'])
            ->where('description', 'LIKE', '%[PENYESUAIAN]%')
            ->latest('date')
            ->paginate(15);

        // Ambil daftar akun untuk form penyesuaian manual/depresiasi
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();
        $branches = Branch::where('is_active', true)->get();

        return view('adjustments.index', compact('adjustments', 'accounts', 'branches'));
    }

    /**
     * Fitur Otomatisasi Jurnal Penyesuaian Penyusutan Aset (Depreciation)
     */
    public function runDepreciation(Request $request)
    {
        $request->validate([
            'branch_id' => ['nullable', 'exists:branches,id'],
            'asset_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'expense_account_id' => ['required', 'exists:chart_of_accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($request) {
            $lastId = Journal::max('id') ?? 0;
            $referenceNumber = 'ADJ-' . date('Ym') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            /** @var \App\Models\Journal $journal */
            $journal = new Journal([
                'branch_id' => $request->branch_id,
                'reference_number' => $referenceNumber,
                'date' => $request->date,
                'description' => '[PENYESUAIAN] Penyusutan Aset: ' . ($request->description ?? 'Beban Penyusutan Periode Ini'),
                'status' => 'posted',
                'created_by' => auth()->id(),
            ]);
            $journal->save();

            // Item 1: Debet pada Akun Beban Penyusutan
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $request->expense_account_id,
                'memo' => 'Beban Penyusutan Aset Tetap',
                'debit' => $request->amount,
                'credit' => 0,
            ]);

            // Item 2: Kredit pada Akun Akumulasi Penyusutan
            JournalItem::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $request->asset_account_id,
                'memo' => 'Akumulasi Penyusutan Aset Tetap',
                'debit' => 0,
                'credit' => $request->amount,
            ]);
        });

        return redirect()->route('adjustments.index')
            ->with('success', 'Jurnal penyesuaian penyusutan berhasil dicatat dan diposting.');
    }
}
