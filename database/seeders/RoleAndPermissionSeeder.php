<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions (Hak Akses)
        $permissions = [
            'view-dashboard',
            'manage-accounts',   // CRUD Chart of Accounts
            'create-journal',    // Entri Jurnal
            'view-reports',      // Lihat Laporan Keuangan
            'manage-users',      // Kelola Pengguna
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Role & Assign Permissions

        // Role STAFF: Hanya bisa input jurnal & lihat laporan
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $staffRole->syncPermissions([
            'view-dashboard',
            'create-journal',
            'view-reports',
        ]);

        // Role ADMIN: Memiliki seluruh hak akses
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        $adminEmail = env('SEED_ADMIN_EMAIL');
        $adminPassword = env('SEED_ADMIN_PASSWORD');

        if (filled($adminEmail) !== filled($adminPassword)) {
            throw new \InvalidArgumentException('Set both SEED_ADMIN_EMAIL and SEED_ADMIN_PASSWORD to create the initial admin account.');
        }

        if (filled($adminEmail)) {
            if (mb_strlen($adminPassword) < 12) {
                throw new \InvalidArgumentException('SEED_ADMIN_PASSWORD must be at least 12 characters long.');
            }

            $adminUser = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => 'Administrator Utama',
                    'password' => Hash::make($adminPassword),
                    'role' => 'admin',
                ]
            );

            $adminUser->syncRoles([$adminRole]);
        }
    }
}
