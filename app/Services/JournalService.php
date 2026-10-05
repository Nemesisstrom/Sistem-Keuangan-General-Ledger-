<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\JournalItem;
use Illuminate\Support\Facades\DB;

class JournalService
{
    /**
     * Membuat transaksi jurnal baru beserta baris detailnya (debit/kredit).
     */
    public function createJournal(array $data, ?int $userId = null): Journal
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Buat Header Jurnal
            $journal = Journal::create([
                'branch_id'        => $data['branch_id'],
                'user_id'          => $userId,
                'transaction_date' => $data['transaction_date'],
                'description'      => $data['description'],
                'status'           => 'posted',
            ]);

            // 2. Buat Detail Item Jurnal (Debit / Kredit)
            foreach ($data['items'] as $item) {
                JournalItem::create([
                    'journal_id' => $journal->id,
                    'account_id' => $item['account_id'],
                    'debit'      => $item['debit'],
                    'credit'     => $item['credit'],
                ]);
            }

            return $journal;
        });
    }

    /**
     * Membatalkan (Void) transaksi jurnal.
     */
    public function voidJournal(Journal $journal): Journal
    {
        return DB::transaction(function () use ($journal) {
            $journal->update([
                'status' => 'voided',
            ]);

            return $journal;
        });
    }
}
