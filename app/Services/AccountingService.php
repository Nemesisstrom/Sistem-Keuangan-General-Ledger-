<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\TaxRecord;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Catat Penjualan dengan PPN Otomatis
     */
    public function createSalesInvoice(array $data)
    {
        return DB::transaction(function () use ($data) {
            $branchId = $data['branch_id']; // SR1 / SR2
            $dpp = $data['amount'];
            $ppnRate = 11; // 11% PPN
            $ppnAmount = $dpp * ($ppnRate / 100);
            $totalAmount = $dpp + $ppnAmount;

            // 1. Header Jurnal
            $journal = Journal::create([
                'branch_id' => $branchId,
                'transaction_number' => 'INV/' . $branchId . '/' . date('YmdHis'),
                'transaction_date' => now(),
                'description' => 'Penjualan Barang / Jasa',
                'created_by' => auth()->id(),
            ]);

            // 2. Detail Jurnal
            // Debit: Kas/Piutang (Total)
            JournalDetail::create([
                'journal_id' => $journal->id,
                'account_id' => $data['kas_account_id'],
                'debit' => $totalAmount,
                'credit' => 0,
            ]);

            // Kredit: Pendapatan (DPP)
            JournalDetail::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $data['revenue_account_id'],
                'debit' => 0,
                'credit' => $dpp,
            ]);

            // Kredit: Utang PPN / PPN Keluaran
            JournalDetail::create([
                'journal_entry_id' => $journal->id,
                'account_id' => $data['ppn_account_id'],
                'debit' => 0,
                'credit' => $ppnAmount,
            ]);

            // 3. Record Log Pajak Otomatis
            TaxRecord::create([
                'journal_entry_id' => $journal->id,
                'tax_type' => 'PPN_OUT',
                'taxable_amount' => $dpp,
                'tax_rate' => $ppnRate,
                'tax_amount' => $ppnAmount,
            ]);

            return $journal;
        });
    }
}
