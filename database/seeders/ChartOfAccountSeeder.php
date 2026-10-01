<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // ==================== 1. ASET (ASSETS) ====================
            ['code' => '1000', 'name' => 'Aset Lancar', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => null],
            ['code' => '1101', 'name' => 'Kas Utama', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1102', 'name' => 'Bank BCA', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1103', 'name' => 'Bank Mandiri', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1201', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1301', 'name' => 'Persediaan Barang Dagang', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1401', 'name' => 'PPN Masukan (Pajak Dibayar di Muka)', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['code' => '1402', 'name' => 'PPh 23 Dibayar di Muka', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],

            ['code' => '1500', 'name' => 'Aset Tetap', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => null],
            ['code' => '1501', 'name' => 'Peralatan Kantor', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1500'],
            ['code' => '1502', 'name' => 'Akumulasi Penyusutan Peralatan Kantor', 'type' => 'asset', 'normal_balance' => 'credit', 'parent_code' => '1500'],
            ['code' => '1503', 'name' => 'Kendaraan', 'type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1500'],
            ['code' => '1504', 'name' => 'Akumulasi Penyusutan Kendaraan', 'type' => 'asset', 'normal_balance' => 'credit', 'parent_code' => '1500'],

            // ==================== 2. KEWAJIBAN / UTANG (LIABILITIES) ====================
            ['code' => '2000', 'name' => 'Kewajiban Jangka Pendek', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => null],
            ['code' => '2101', 'name' => 'Utang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],
            ['code' => '2201', 'name' => 'Utang Gaji', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],
            ['code' => '2301', 'name' => 'Utang PPN Keluaran', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],
            ['code' => '2302', 'name' => 'Utang PPh 21', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],
            ['code' => '2303', 'name' => 'Utang PPh 23', 'type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],

            // ==================== 3. EKUITAS / MODAL (EQUITY) ====================
            ['code' => '3000', 'name' => 'Ekuitas', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => null],
            ['code' => '3101', 'name' => 'Modal Disetor', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3000'],
            ['code' => '3201', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3000'],
            ['code' => '3301', 'name' => 'Laba Tahun Berjalan', 'type' => 'equity', 'normal_balance' => 'credit', 'parent_code' => '3000'],

            // ==================== 4. PENDAPATAN (REVENUE) ====================
            ['code' => '4000', 'name' => 'Pendapatan Usaha', 'type' => 'revenue', 'normal_balance' => 'credit', 'parent_code' => null],
            ['code' => '4101', 'name' => 'Pendapatan Penjualan', 'type' => 'revenue', 'normal_balance' => 'credit', 'parent_code' => '4000'],
            ['code' => '4102', 'name' => 'Pendapatan Jasa', 'type' => 'revenue', 'normal_balance' => 'credit', 'parent_code' => '4000'],
            ['code' => '4201', 'name' => 'Diskon Penjualan', 'type' => 'revenue', 'normal_balance' => 'debit', 'parent_code' => '4000'],

            // ==================== 5. BEBAN (EXPENSES) ====================
            ['code' => '5000', 'name' => 'Beban Pokok Penjualan', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => null],
            ['code' => '5101', 'name' => 'Harga Pokok Penjualan (HPP)', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '5000'],

            ['code' => '6000', 'name' => 'Beban Operasional & Penggajian', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => null],
            ['code' => '6101', 'name' => 'Beban Gaji Pokok', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6102', 'name' => 'Beban Tunjangan Karyawan', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6103', 'name' => 'Beban BPJS & Asuransi', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6201', 'name' => 'Beban Listrik, Air & Internet', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6202', 'name' => 'Beban Sewa Gedung', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6203', 'name' => 'Beban Penyusutan Aset Tetap', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
            ['code' => '6301', 'name' => 'Beban Pemasaran & Promosi', 'type' => 'expense', 'normal_balance' => 'debit', 'parent_code' => '6000'],
        ];

        // 1. First pass: Simpan semua akun tanpa parent_id
        foreach ($accounts as $acc) {
            ChartOfAccount::updateOrCreate(
                ['code' => $acc['code']],
                [
                    'name'           => $acc['name'],
                    'type'           => $acc['type'],
                    'normal_balance' => $acc['normal_balance'],
                    'is_active'      => true,
                ]
            );
        }

        // 2. Second pass: Update parent_id berdasarkan parent_code
        foreach ($accounts as $acc) {
            if ($acc['parent_code']) {
                $parent = ChartOfAccount::where('code', $acc['parent_code'])->first();
                if ($parent) {
                    ChartOfAccount::where('code', $acc['code'])->update([
                        'parent_id' => $parent->id,
                    ]);
                }
            }
        }
    }
}
