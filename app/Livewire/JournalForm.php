<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Services\JournalService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class JournalForm extends Component
{
    public $branch_id;

    public $date;

    public $description;

    // Array dinamis untuk menyimpan baris jurnal
    public array $items = [];

    public $totalDebit = 0;

    public $totalCredit = 0;

    public $isBalanced = false;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');

        // Atur cabang default jika user bukan Super Admin
        if (Auth::check() && Auth::user()->role !== 'superadmin') {
            $this->branch_id = Auth::user()->branch_id
                ?? Branch::where('is_active', true)->value('id');
        } else {
            $this->branch_id = Branch::first()?->id;
        }

        // Default: Sediakan 2 baris awal (Debit & Kredit)
        $this->addItem();
        $this->addItem();
    }

    // Tambah baris baru
    public function addItem()
    {
        $this->items[] = [
            'account_id' => '',
            'debit' => 0,
            'credit' => 0,
            'description' => '',
        ];
    }

    // Hapus baris tertentu
    public function removeItem($index)
    {
        if (count($this->items) > 2) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
            $this->calculateTotals();
        } else {
            session()->flash('warning', 'Transaksi jurnal minimal membutuhkan 2 baris (Debit & Kredit).');
        }
    }

    // Listener realtime saat nilai debit/credit diubah
    public function updatedItems()
    {
        $this->calculateTotals();
    }

    // Hitung total Debit, Kredit, dan Cek Keseimbangan
    public function calculateTotals()
    {
        $this->totalDebit = array_reduce($this->items, function ($sum, $item) {
            return $sum + (float) ($item['debit'] ?? 0);
        }, 0);

        $this->totalCredit = array_reduce($this->items, function ($sum, $item) {
            return $sum + (float) ($item['credit'] ?? 0);
        }, 0);

        $this->isBalanced = (round($this->totalDebit, 2) === round($this->totalCredit, 2))
                            && $this->totalDebit > 0;
    }

    public function save(JournalService $journalService)
    {
        $this->validate([
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.debit' => 'required|numeric|min:0',
            'items.*.credit' => 'required|numeric|min:0',
        ], [
            'items.*.account_id.required' => 'Pilih akun untuk semua baris.',
        ]);

        $this->calculateTotals();

        if (! $this->isBalanced) {
            session()->flash('error', 'Gagal menyimpan: Total Debit dan Kredit harus seimbang!');

            return;
        }

        $journal = $journalService->createEntry(
            (int) $this->branch_id,
            $this->date,
            $this->description,
            $this->items,
            Auth::id()
        );

        return redirect()->route('journals.show', $journal);
    }

    public function render()
    {
        return view('livewire.journal-form', [
            'branches' => Branch::where('is_active', true)->get(),
            'accounts' => ChartOfAccount::where('is_active', true)
                ->orderBy('code')
                ->get(),
        ]);
    }
}
