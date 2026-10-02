<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\TaxLog;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    /**
     * Daftar Master Pajak (PPN/PPh).
     */
    public function index()
    {
        $taxes = Tax::with('account')->where('is_active', true)->get();
        return response()->json($taxes);
    }

    /**
     * Simpan master pajak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'       => 'required|string|max:10|unique:taxes,code',
            'name'       => 'required|string|max:255',
            'category'   => 'required|in:PPN,PPh',
            'rate'       => 'required|numeric|min:0|max:100',
            'account_id' => 'required|exists:chart_of_accounts,id',
        ]);

        $tax = Tax::create($validated);

        return response()->json(['message' => 'Master pajak berhasil dibuat.', 'data' => $tax], 201);
    }

    /**
     * Rekap/Log Pajak per periode/kategori.
     */
    public function logs(Request $request)
    {
        $request->validate([
            'category'   => 'nullable|in:PPN,PPh',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
        ]);

        $logs = TaxLog::with(['branch', 'journal', 'tax'])
            ->when($request->category, function ($query, $category) {
                $query->whereHas('tax', fn($q) => $q->where('category', $category));
            })
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereHas('journal', function ($q) use ($request) {
                    $q->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
                });
            })
            ->latest('id')
            ->paginate(20);

        return response()->json($logs);
    }
}
