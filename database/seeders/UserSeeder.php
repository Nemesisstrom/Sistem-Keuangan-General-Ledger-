<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin Utama
        User::create([
            'name' => 'Administrator Keuangan',
            'email' => 'admin@keuangan.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Buat Akun Staff Keuangan
        User::create([
            'name' => 'Taufik Baidawi',
            'email' => 'accountant@keuangan.com',
            'password' => Hash::make('password123'),
            'role' => 'accountant',
            'is_active' => true,
        ]);
    }
}
