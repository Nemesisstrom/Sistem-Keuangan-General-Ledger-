<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'code'      => 'SR1',
                'name'      => 'Cabang Utama - SR1',
                'address'   => 'Jl. Jendral Sudirman No. 1, Jakarta Central',
                'phone'     => '021-5550101',
                'is_active' => true,
            ],
            [
                'code'      => 'SR2',
                'name'      => 'Cabang Pembantu - SR2',
                'address'   => 'Jl. Raya Bandung No. 88, Bandung',
                'phone'     => '022-7770202',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['code' => $branch['code']], $branch);
        }
    }
}
