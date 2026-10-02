<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Services\JournalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    protected JournalService $journalService;

    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    /**
     * Tampilkan daftar transaksi jurnal.
     */
    public function index(Request $request)
    {
        $request->validate([
            'branch_id'  => 'nullable|exists:branches,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'nullable|in:draft,posted,voided',
            'per_page'   => 'nullable|integer|min:5|max:100',
        ]);

        $journals = Journal::with(['branch', 'items.account', 'user'])
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->whereDate('transaction_date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->whereDate('transaction_date', '<=', $request->end_date);
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest('transaction_date')
            ->latest('id')
            ->paginate($request->get('per_page', 20));

        return response()->json($journals);
    }

    /**
     * Buat transaksi jurnal umum baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'          => 'required|exists:branches,id',
            'transaction_date'   => 'required|date',
            'description'        => 'required|string|max:255',
            'items'              => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.debit'      => 'required|numeric|min:0',
            'items.*.credit'     => 'required|numeric|min:0',
        ]);

        // 1. Validasi: Item tidak boleh memiliki debit DAN kredit > 0 bersamaan
        foreach ($validated['items'] as $index => $item) {
            if ($item['debit'] > 0 && $item['credit'] > 0) {
                return response()->json([
                    'message' => "Baris ke-" . ($index + 1) . " tidak valid. Debit dan Kredit tidak boleh diisi bersamaan."
                ], 422);
            }
        }

        // 2. Validasi: Keseimbangan (Balance) Debit & Kredit
        $totalDebit  = collect($validated['items'])->sum('debit');
        $totalCredit = collect($validated['items'])->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.0001) {
            return response()->json([
                'message'      => 'Transaksi tidak seimbang! Total Debit dan Kredit harus sama.',
                'total_debit'  => $totalDebit,
                'total_credit' => $totalCredit,
            ], 422);
        }

        // 3. Eksekusi Pembuatan Jurnal dengan DB Transaction
        $journal = DB::transaction(function () use ($validated) {
            return $this->journalService->createJournal($validated, Auth::id()); // 2. Gunakan Auth::id()
        });

        return response()->json([
            'message' => 'Jurnal transaksi berhasil dicatat.',
            'data'    => $journal->load(['branch', 'items.account']),
        ], 201);
    }

    /**
     * Tampilkan detail transaksi jurnal.
     */
    public function show(Journal $journal)
    {
        return response()->json(
            $journal->load(['branch', 'items.account', 'user'])
        );
    }

    /**
     * Membatalkan / Void transaksi jurnal.
     */
    public function void(Journal $journal)
    {
        if ($journal->status === 'voided') {
            return response()->json([
                'message' => 'Jurnal ini sudah dibatalkan (void) sebelumnya.'
            ], 422);
        }

        DB::transaction(function () use ($journal) {
            $this->journalService->voidJournal($journal);
        });

        return response()->json([
            'message' => 'Jurnal berhasil dibatalkan (void).'
        ]);
    }
}
