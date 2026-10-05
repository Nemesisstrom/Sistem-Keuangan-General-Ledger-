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
                'code' => 'SR1',
                'name' => 'Cabang Utama - SR1 (Jakarta)',
                'address' => 'Jl. Jend. Sudirman No. 10, Jakarta Selatan',
                'phone' => '021-5550101',
                'is_active' => true,
            ],
            [
                'code' => 'SR2',
                'name' => 'Cabang Operasional - SR2 (Surabaya)',
                'address' => 'Jl. Pemuda No. 45, Surabaya',
                'phone' => '031-5550202',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['code' => $branch['code']], $branch);
        }
    }
}
