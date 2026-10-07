<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Journal;
use App\Models\JournalItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JournalService
{
    public function createEntry(
        int $branchId,
        string $date,
        string $description,
        array $items,
        ?int $userId = null
    ): Journal {
        return $this->createJournal([
            'branch_id' => $branchId,
            'date' => $date,
            'description' => $description,
            'items' => $items,
        ], $userId);
    }

    public function createJournal(array $data, ?int $userId = null): Journal
    {
        $date = $data['date'] ?? $data['transaction_date'] ?? null;
        $items = $data['items'];

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($items as $index => $item) {
            $debit = (float) ($item['debit'] ?? 0);
            $credit = (float) ($item['credit'] ?? 0);

            if ($debit > 0 && $credit > 0) {
                throw ValidationException::withMessages([
                    "items.$index.debit" => 'Satu baris hanya boleh memiliki debit atau kredit.',
                ]);
            }

            $totalDebit += $debit;
            $totalCredit += $credit;
        }

        if ($totalDebit <= 0 || round($totalDebit, 2) !== round($totalCredit, 2)) {
            throw ValidationException::withMessages([
                'items' => 'Total debit dan kredit harus sama dan lebih besar dari nol.',
            ]);
        }

        return DB::transaction(function () use ($data, $date, $items, $userId) {
            $branchId = (int) $data['branch_id'];
            Branch::whereKey($branchId)->lockForUpdate()->firstOrFail();
            $entryNumber = $this->nextEntryNumber($branchId, $date);

            $journal = Journal::create([
                'branch_id' => $branchId,
                'entry_number' => $entryNumber,
                'date' => $date,
                'description' => $data['description'],
                'status' => 'posted',
                'created_by' => $userId,
            ]);

            foreach ($items as $item) {
                JournalItem::create([
                    'journal_entry_id' => $journal->id,
                    'account_id' => $item['account_id'],
                    'debit' => $item['debit'] ?? 0,
                    'credit' => $item['credit'] ?? 0,
                    'description' => $item['description'] ?? null,
                ]);
            }

            return $journal->load(['branch', 'items.account', 'creator']);
        });
    }

    private function nextEntryNumber(int $branchId, string $date): string
    {
        $prefix = sprintf('JRNL-%d-%s-', $branchId, str_replace('-', '', $date));
        $lastNumber = Journal::withoutBranchScope()
            ->where('entry_number', 'like', $prefix.'%')
            ->orderByDesc('entry_number')
            ->value('entry_number');

        $sequence = $lastNumber ? (int) substr($lastNumber, -4) + 1 : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
