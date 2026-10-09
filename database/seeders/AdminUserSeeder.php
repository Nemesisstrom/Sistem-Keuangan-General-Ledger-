<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Semua Permission Sistem
        $permissions = [
            'view-dashboard',
            'manage-users',
            'manage-accounts',
            'create-journal',
            'post-journal',
            'reverse-journal',
            'manage-reconciliation',
            'manage-adjustments',
            'view-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Role Admin dan Berikan Seluruh Permission
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        // 3. Buat/Update User Admin Utama
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@keuangan.test');
        $adminPassword = env('SEED_ADMIN_PASSWORD', 'Administrator123!');

        $adminUser = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'      => 'Administrator Utama',
                'password'  => Hash::make($adminPassword),
                'branch_id' => null, // null berarti memiliki akses ke seluruh cabang
                'is_active' => true,
            ]
        );

        // Assign Role Admin ke User
        $adminUser->assignRole($adminRole);
    }
}
