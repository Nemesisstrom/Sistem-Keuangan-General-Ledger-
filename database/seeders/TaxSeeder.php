<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $vatOutputAcc = ChartOfAccount::where('code', '2-1201')->first();
        $vatInputAcc  = ChartOfAccount::where('code', '1-1301')->first();
        $pph21Acc     = ChartOfAccount::where('code', '2-1202')->first();
        $pph23Acc     = ChartOfAccount::where('code', '2-1203')->first();

        $taxes = [
            [
                'code' => 'PPN-OUT',
                'name' => 'PPN Keluaran (11%)',
                'rate' => 11.00,
                'type' => 'vat_output',
                'account_id' => $vatOutputAcc?->id,
            ],
            [
                'code' => 'PPN-IN',
                'name' => 'PPN Masukan (11%)',
                'rate' => 11.00,
                'type' => 'vat_input',
                'account_id' => $vatInputAcc?->id,
            ],
            [
                'code' => 'PPH21',
                'name' => 'PPh Pasal 21 (Gaji/Honorarium)',
                'rate' => 5.00, // Rate dasar/efektif
                'type' => 'pph21',
                'account_id' => $pph21Acc?->id,
            ],
            [
                'code' => 'PPH23',
                'name' => 'PPh Pasal 23 (Jasa - 2%)',
                'rate' => 2.00,
                'type' => 'pph23',
                'account_id' => $pph23Acc?->id,
            ],
        ];

        foreach ($taxes as $tax) {
            if ($tax['account_id']) {
                Tax::updateOrCreate(['code' => $tax['code']], $tax);
            }
        }
    }
}
