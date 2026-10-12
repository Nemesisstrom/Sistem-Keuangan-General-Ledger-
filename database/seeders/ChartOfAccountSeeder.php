<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. KELOMPOK ASSET (KODE 1-xxxx)
        // ==========================================
        $currentAsset = ChartOfAccount::create([
            'code' => '1-1000',
            'name' => 'Aset Lancar (Current Assets)',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1-1101',
            'name' => 'Kas di Kantor',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $currentAsset->id, // Sub-akun dari Aset Lancar
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1-1102',
            'name' => 'Kas di Bank',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $currentAsset->id,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1-1200',
            'name' => 'Piutang Usaha',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $currentAsset->id,
            'is_active' => true,
        ]);

        // ==========================================
        // 2. KELOMPOK LIABILITY / KEWAJIBAN (KODE 2-xxxx)
        // ==========================================
        $currentLiability = ChartOfAccount::create([
            'code' => '2-1000',
            'name' => 'Kewajiban Lancar (Current Liabilities)',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '2-1101',
            'name' => 'Hutang Usaha',
            'type' => 'liability',
            'normal_balance' => 'credit',
            'parent_id' => $currentLiability->id,
            'is_active' => true,
        ]);

        // ==========================================
        // 3. KELOMPOK EQUITY / EKUITAS (KODE 3-xxxx)
        // ==========================================
        $equity = ChartOfAccount::create([
            'code' => '3-1000',
            'name' => 'Ekuitas Pemilik (Equity)',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '3-1101',
            'name' => 'Modal Disetor',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'parent_id' => $equity->id,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '3-3001',
            'name' => 'Laba Tahun Berjalan (Current Year Earnings)',
            'type' => 'equity',
            'normal_balance' => 'credit',
            'parent_id' => $equity->id,
            'is_active' => true,
        ]);

        // ==========================================
        // 4. KELOMPOK REVENUE / PENDAPATAN (KODE 4-xxxx)
        // ==========================================
        $revenue = ChartOfAccount::create([
            'code' => '4-1000',
            'name' => 'Pendapatan Usaha (Revenues)',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '4-1101',
            'name' => 'Pendapatan Layanan Jasa',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'parent_id' => $revenue->id,
            'is_active' => true,
        ]);

        // ==========================================
        // 5. KELOMPOK EXPENSE / BEBAN (KODE 5-xxxx)
        // ==========================================
        $expense = ChartOfAccount::create([
            'code' => '5-1000',
            'name' => 'Beban Operasional (Operating Expenses)',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '5-1101',
            'name' => 'Beban Gaji Karyawan',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'parent_id' => $expense->id,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '5-1102',
            'name' => 'Beban Sewa & Utilitas',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'parent_id' => $expense->id,
            'is_active' => true,
        ]);
    }
}
