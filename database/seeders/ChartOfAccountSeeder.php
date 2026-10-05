<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // ==========================================
            // 1. ASET (ASSETS)
            // ==========================================
            ['code' => '1-0000', 'name' => 'ASET', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => null],

            // Aset Lancar
            ['code' => '1-1000', 'name' => 'Aset Lancar', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-0000'],
            ['code' => '1-1100', 'name' => 'Kas & Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1101', 'name' => 'Kas Kecil (Petty Cash)', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],
            ['code' => '1-1102', 'name' => 'Bank BCA', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],
            ['code' => '1-1103', 'name' => 'Bank Mandiri', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1100'],

            ['code' => '1-1200', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1201', 'name' => 'Piutang Dagang', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1200'],
            ['code' => '1-1202', 'name' => 'Cadangan Kerugian Piutang', 'type' => 'asset', 'normal_balance' => 'credit', 'parent_code' => '1-1200'],

            ['code' => '1-1300', 'name' => 'Pajak Dibayar Di Muka', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1000'],
            ['code' => '1-1301', 'name' => 'PPN Masukan (VAT Input)', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1300'],
            ['code' => '1-1302', 'name' => 'PPh Pasal 23 Dibayar Di Muka', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-1300'],

            // Aset Tetap
            ['code' => '1-2000', 'name' => 'Aset Tetap', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-0000'],
            ['code' => '1-2101', 'name' => 'Peralatan Kantor', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1-2000'],
            ['code' => '1-2102', 'name' => 'Akumulasi Penyusutan Peralatan Kantor', 'type' => 'asset', 'normal_balance' => 'credit', 'parent_code' => '1-2000'],

            // ==========================================
            // 2. KEWAJIBAN / UTANG (LIABILITIES)
            // ==========================================
            ['code' => '2-0000', 'name' => 'KEWAJIBAN', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => null],

            ['code' => '2-1000', 'name' => 'Kewajiban Jangka Pendek', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-0000'],
            ['code' => '2-1101', 'name' => 'Utang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1000'],
            ['code' => '2-1200', 'name' => 'Utang Pajak', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1000'],
            ['code' => '2-1201', 'name' => 'Utang PPN Keluaran (VAT Output)', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1200'],
            ['code' => '2-1202', 'name' => 'Utang PPh Pasal 21 (Gaji)', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1200'],
            ['code' => '2-1203', 'name' => 'Utang PPh Pasal 23', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1200'],
            ['code' => '2-1301', 'name' => 'Utang Gaji & Tunjangan', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2-1000'],

            // ==========================================
            // 3. EKUITAS / MODAL (EQUITY)
            // ==========================================
            ['code' => '3-0000', 'name' => 'EKUITAS', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => null],
            ['code' => '3-1001', 'name' => 'Modal Disetor', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3-0000'],
            ['code' => '3-2001', 'name' => 'Laba Ditahan (Retained Earnings)', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3-0000'],
            ['code' => '3-3001', 'name' => 'Laba Tahun Berjalan', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3-0000'],

            // ==========================================
            // 4. PENDAPATAN (REVENUE)
            // ==========================================
            ['code' => '4-0000', 'name' => 'PENDAPATAN', 'type' => 'revenue', 'normal_balance' => 'credit', 'parent_code' => null],
            ['code' => '4-1001', 'name' => 'Pendapatan Penjualan / Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'parent_code' => '4-0000'],
            ['code' => '4-2001', 'name' => 'Diskon Penjualan', 'type' => 'revenue', 'normal_balance' => 'debit', 'parent_code' => '4-0000'],

            // ==========================================
            // 5. BEBAN & BIAYA (EXPENSES)
            // ==========================================
            ['code' => '5-0000', 'name' => 'BEBAN OPERASIONAL', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => null],
            ['code' => '5-1000', 'name' => 'Beban Gaji & Personalia', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-0000'],
            ['code' => '5-1001', 'name' => 'Beban Gaji Pokok', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['code' => '5-1002', 'name' => 'Beban Tunjangan & Bonus', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],
            ['code' => '5-1003', 'name' => 'Beban BPJS Ketenagakerjaan & Kesehatan', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-1000'],

            ['code' => '5-2000', 'name' => 'Beban Umum & Administrasi', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-0000'],
            ['code' => '5-2001', 'name' => 'Beban Listrik, Air & Internet', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2002', 'name' => 'Beban Sewa Kantor', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
            ['code' => '5-2003', 'name' => 'Beban Penyusutan Peralatan', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5-2000'],
        ];

        // First pass: Insert semua account tanpa parent_id agar constraint FK tidak error
        foreach ($accounts as $acc) {
            ChartOfAccount::updateOrCreate(
                ['code' => $acc['code']],
                [
                    'name' => $acc['name'],
                    'type' => $acc['type'],
                    'normal_balance' => $acc['normal_balance'],
                    'is_active' => true,
                ]
            );
        }

        // Second pass: Update parent_id berdasarkan parent_code
        foreach ($accounts as $acc) {
            if ($acc['parent_code']) {
                $parent = ChartOfAccount::where('code', $acc['parent_code'])->first();
                if ($parent) {
                    ChartOfAccount::where('code', $acc['code'])->update([
                        'parent_id' => $parent->id
                    ]);
                }
            }
        }
    }
}
