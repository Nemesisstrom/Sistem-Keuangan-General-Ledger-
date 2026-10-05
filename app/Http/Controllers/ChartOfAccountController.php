<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    /**
     * Tampilkan semua daftar Chart of Accounts.
     */
    public function index(Request $request)
    {
        $accounts = ChartOfAccount::query()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('code', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $accounts
        ]);
    }

    /**
     * Simpan akun baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:chart_of_accounts,code',
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|in:asset,liability,equity,revenue,expense,cost_of_goods_sold',
            'normal_balance' => 'required|string|in:debit,credit',
            'is_active'      => 'boolean',
        ]);

        $account = ChartOfAccount::create($validated);

        return response()->json([
            'message' => 'Akun COA berhasil dibuat.',
            'data'    => $account
        ], 201);
    }

    /**
     * Detail informasi akun.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        return response()->json([
            'status' => 'success',
            'data'   => $chartOfAccount
        ]);
    }

    /**
     * Perbarui akun.
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code'           => 'required|string|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'name'           => 'required|string|max:255',
            'type'           => 'required|string|in:asset,liability,equity,revenue,expense,cost_of_goods_sold',
            'normal_balance' => 'required|string|in:debit,credit',
            'is_active'      => 'boolean',
        ]);

        $chartOfAccount->update($validated);

        return response()->json([
            'message' => 'Akun COA berhasil diperbarui.',
            'data'    => $chartOfAccount
        ]);
    }

    /**
     * Hapus akun.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        // Cek apakah akun sudah pernah digunakan di transaksi
        if ($chartOfAccount->journalItems()->exists()) {
            return response()->json([
                'message' => 'Gagal menghapus! Akun ini sudah memiliki riwayat transaksi.'
            ], 422);
        }

        $chartOfAccount->delete();

        return response()->json([
            'message' => 'Akun COA berhasil dihapus.'
        ]);
    }
}
