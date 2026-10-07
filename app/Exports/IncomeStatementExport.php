<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use App\Models\JournalItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncomeStatementExport implements FromCollection, WithHeadings
{
    private string $startDate;

    private string $endDate;

    public function __construct(?string $startDate = null, ?string $endDate = null)
    {
        $this->startDate = $startDate ?? now()->startOfMonth()->toDateString();
        $this->endDate = $endDate ?? now()->endOfMonth()->toDateString();
    }

    public function collection(): Collection
    {
        $accountBalances = JournalItem::query()
            ->select(
                'chart_of_accounts.id',
                'chart_of_accounts.type',
                'chart_of_accounts.normal_balance',
                DB::raw('SUM(journal_items.debit) as total_debit'),
                DB::raw('SUM(journal_items.credit) as total_credit')
            )
            ->join('chart_of_accounts', 'journal_items.account_id', '=', 'chart_of_accounts.id')
            ->join('journal_entries', 'journal_items.journal_entry_id', '=', 'journal_entries.id')
            ->whereIn('chart_of_accounts.type', ['revenue', 'expense'])
            ->whereBetween('journal_entries.date', [$this->startDate, $this->endDate])
            ->where('journal_entries.status', 'posted')
            ->groupBy(
                'chart_of_accounts.id',
                'chart_of_accounts.type',
                'chart_of_accounts.normal_balance'
            )
            ->get()
            ->keyBy('id');

        $accounts = ChartOfAccount::query()
            ->whereIn('type', ['revenue', 'expense'])
            ->orderBy('code')
            ->get();

        $rows = collect();
        $totalRevenue = 0.0;
        $totalExpense = 0.0;

        $rows->push(['-- PENDAPATAN --', '', '']);
        foreach ($accounts->where('type', 'revenue') as $account) {
            $balance = $accountBalances->get($account->id);
            $debit = (float) ($balance->total_debit ?? 0);
            $credit = (float) ($balance->total_credit ?? 0);
            $amount = $account->normal_balance === 'credit' ? $credit - $debit : $debit - $credit;
            $totalRevenue += $amount;

            $rows->push([$account->code, $account->name, $amount]);
        }
        $rows->push(['TOTAL PENDAPATAN', '', $totalRevenue]);
        $rows->push(['', '', '']);

        $rows->push(['-- BEBAN OPERASIONAL --', '', '']);
        foreach ($accounts->where('type', 'expense') as $account) {
            $balance = $accountBalances->get($account->id);
            $debit = (float) ($balance->total_debit ?? 0);
            $credit = (float) ($balance->total_credit ?? 0);
            $amount = $account->normal_balance === 'credit' ? $credit - $debit : $debit - $credit;
            $totalExpense += $amount;

            $rows->push([$account->code, $account->name, $amount]);
        }
        $rows->push(['TOTAL BEBAN', '', $totalExpense]);
        $rows->push(['', '', '']);
        $rows->push(['LABA / RUGI BERSIH', '', $totalRevenue - $totalExpense]);

        return $rows;
    }

    public function headings(): array
    {
        return ['Kode Akun', 'Nama Akun / Kategori', 'Nominal (Rp)'];
    }
}
