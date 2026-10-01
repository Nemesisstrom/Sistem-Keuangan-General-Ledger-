<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $ppnKeluaranAcc = ChartOfAccount::where('code', '2301')->first();
        $ppnMasukanAcc  = ChartOfAccount::where('code', '1401')->first();
        $pph21Acc       = ChartOfAccount::where('code', '2302')->first();
        $pph23Acc       = ChartOfAccount::where('code', '2303')->first();

        $taxes = [
            [
                'code'       => 'PPN-OUT',
                'name'       => 'PPN Keluaran (11%)',
                'category'   => 'PPN',
                'rate'       => 11.00,
                'account_id' => $ppnKeluaranAcc?->id,
                'is_active'  => true,
            ],
            [
                'code'       => 'PPN-IN',
                'name'       => 'PPN Masukan (11%)',
                'category'   => 'PPN',
                'rate'       => 11.00,
                'account_id' => $ppnMasukanAcc?->id,
                'is_active'  => true,
            ],
            [
                'code'       => 'PPH21',
                'name'       => 'PPh Pasal 21 (Gaji)',
                'category'   => 'PPh',
                'rate'       => 5.00, // Tarif dasar / fleksibel
                'account_id' => $pph21Acc?->id,
                'is_active'  => true,
            ],
            [
                'code'       => 'PPH23',
                'name'       => 'PPh Pasal 23 (Jasa 2%)',
                'category'   => 'PPh',
                'rate'       => 2.00,
                'account_id' => $pph23Acc?->id,
                'is_active'  => true,
            ],
        ];

        foreach ($taxes as $tax) {
            if ($tax['account_id']) {
                Tax::updateOrCreate(['code' => $tax['code']], $tax);
            }
        }
    }
}
