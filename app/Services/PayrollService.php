<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\Employee;
use App\Models\Journal;
use App\Models\JournalItem;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Memproses penggajian karyawan dan otomatis mencatat jurnal umum.
     */
    public function processPayroll(Employee $employee, string $period, float $allowances = 0, float $deductions = 0): Payroll
    {
        return DB::transaction(function () use ($employee, $period, $allowances, $deductions) {
            $basicSalary = (float) $employee->basic_salary;

            // Hitung estimasi PPh 21 sederhana (dapat disesuaikan dengan aturan PTKP/TER)
            $grossSalary = $basicSalary + $allowances;
            $pph21 = $this->calculatePph21($grossSalary, $employee->ptkp_status);

            $netSalary = $grossSalary - $deductions - $pph21;

            // 1. Buat Jurnal Otomatis untuk Beban Gaji
            $journal = $this->createPayrollJournal($employee, $period, $basicSalary, $allowances, $deductions, $pph21, $netSalary);

            // 2. Simpan Transaksi Payroll
            $payroll = Payroll::create([
                'employee_id'  => $employee->id,
                'branch_id'    => $employee->branch_id,
                'journal_id'   => $journal->id,
                'period'       => $period,
                'basic_salary' => $basicSalary,
                'allowances'   => $allowances,
                'deductions'   => $deductions,
                'pph21_amount' => $pph21,
                'net_salary'   => $netSalary,
                'status'       => 'processed',
            ]);

            return $payroll;
        });
    }

    /**
     * Otomatisasi pencatatan jurnal umum penggajian.
     */
    protected function createPayrollJournal(Employee $employee, string $period, float $basicSalary, float $allowances, float $deductions, float $pph21, float $netSalary): Journal
    {
        // Cari COA terkait (Sesuaikan dengan Kode Akun di COA Anda)
        $expenseAccount = ChartOfAccount::where('code', '5-50001')->first() ?? ChartOfAccount::where('type', 'expense')->first();
        $cashAccount    = ChartOfAccount::where('code', '1-10001')->first() ?? ChartOfAccount::where('type', 'asset')->first();
        $taxAccount     = ChartOfAccount::where('code', '2-20001')->first() ?? ChartOfAccount::where('type', 'liability')->first();

        $journal = Journal::create([
            'branch_id'        => $employee->branch_id,
            'user_id'          => Auth::id(),
            'transaction_date' => now()->format('Y-m-d'),
            'description'      => "Gaji Karyawan {$employee->name} ({$employee->nik}) - Periode {$period}",
            'status'           => 'posted',
        ]);

        $totalBeban = $basicSalary + $allowances;

        // Debit: Beban Gaji
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => $expenseAccount->id,
            'debit'      => $totalBeban,
            'credit'     => 0,
        ]);

        // Kredit: Utang PPh 21 (jika ada)
        if ($pph21 > 0 && $taxAccount) {
            JournalItem::create([
                'journal_id' => $journal->id,
                'account_id' => $taxAccount->id,
                'debit'      => 0,
                'credit'     => $pph21 + $deductions,
            ]);
        }

        // Kredit: Kas / Bank (Gaji Bersih)
        JournalItem::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'debit'      => 0,
            'credit'     => $netSalary,
        ]);

        return $journal;
    }

    /**
     * Hitung PPh 21 Sederhana.
     */
    protected function calculatePph21(float $grossSalary, string $ptkpStatus): float
    {
        // Contoh perhitungan kasar tarif efektif (TER) atau 5%
        if ($grossSalary <= 5400000) {
            return 0;
        }

        return round($grossSalary * 0.05);
    }
}
