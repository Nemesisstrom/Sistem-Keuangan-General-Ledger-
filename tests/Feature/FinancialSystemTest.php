<?php

use App\Models\User;
use App\Models\Branch;
use App\Models\ChartOfAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ==========================================
// PENGUJIAN MODUL CHART OF ACCOUNTS (COA)
// ==========================================

test('admin can view chart of accounts index page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Jika aplikasi menggunakan Gate/Authorization, berikan izin atau gunakan bypass
    // $this->withoutMiddleware(); // Uncomment jika ingin bypass semua middleware untuk pengujian

    $response = $this->actingAs($user)->get(route('accounts.index'));

    // Jika controller mengembalikan 200 setelah bypass middleware
    $response->assertStatus(200);
});

test('admin can create a chart of account with parent hierarchy', function () {
    $user = User::factory()->create();

    $parentAccount = ChartOfAccount::create([
        'code' => '1-1000',
        'name' => 'Aset Lancar',
        'type' => 'asset',
        'normal_balance' => 'debit',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->post(route('accounts.store'), [
        'code' => '1-1101',
        'name' => 'Kas Kecil',
        'type' => 'asset',
        'normal_balance' => 'debit',
        'parent_id' => $parentAccount->id,
        'is_active' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('chart_of_accounts', [
        'code' => '1-1101',
        'parent_id' => $parentAccount->id,
    ]);
});

test('chart of account code must be unique', function () {
    $user = User::factory()->create();

    ChartOfAccount::create([
        'code' => '1-1101',
        'name' => 'Kas',
        'type' => 'asset',
        'normal_balance' => 'debit',
    ]);

    $response = $this->actingAs($user)->post(route('accounts.store'), [
        'code' => '1-1101',
        'name' => 'Kas Duplikat',
        'type' => 'asset',
        'normal_balance' => 'debit',
    ]);

    $response->assertSessionHasErrors('code');
});


// ==========================================
// PENGUJIAN MODUL JURNAL UMUM (JOURNAL ENTRIES)
// ==========================================

test('user can create a balanced journal entry', function () {
    $user = User::factory()->create();
    $branch = Branch::factory()->create();

    $expenseAccount = ChartOfAccount::create([
        'code' => '5-1100',
        'name' => 'Beban Operasional',
        'type' => 'expense',
        'normal_balance' => 'debit',
    ]);

    $assetAccount = ChartOfAccount::create([
        'code' => '1-1101',
        'name' => 'Kas di Bank',
        'type' => 'asset',
        'normal_balance' => 'debit',
    ]);

    $response = $this->actingAs($user)->post(route('journals.store'), [
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'description' => 'Pembayaran biaya operasional',
        'items' => [
            [
                'account_id' => $expenseAccount->id,
                'debit' => 500000,
                'credit' => 0,
                'memo' => 'Beban listrik'
            ],
            [
                'account_id' => $assetAccount->id,
                'debit' => 0,
                'credit' => 500000,
                'memo' => 'Pembayaran kas'
            ],
        ]
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('journal_entries', [
        'description' => 'Pembayaran biaya operasional',
        'status' => 'draft',
    ]);
});

test('journal entry requires total debit to equal total credit', function () {
    $user = User::factory()->create();
    $branch = Branch::factory()->create();

    $expenseAccount = ChartOfAccount::create([
        'code' => '5-1100',
        'name' => 'Beban Operasional',
        'type' => 'expense',
        'normal_balance' => 'debit',
    ]);

    $assetAccount = ChartOfAccount::create([
        'code' => '1-1101',
        'name' => 'Kas di Bank',
        'type' => 'asset',
        'normal_balance' => 'debit',
    ]);

    $response = $this->actingAs($user)->post(route('journals.store'), [
        'branch_id' => $branch->id,
        'date' => now()->toDateString(),
        'description' => 'Jurnal tidak seimbang',
        'items' => [
            [
                'account_id' => $expenseAccount->id,
                'debit' => 500000,
                'credit' => 0,
                'memo' => ''
            ],
            [
                'account_id' => $assetAccount->id,
                'debit' => 0,
                'credit' => 400000,
                'memo' => ''
            ],
        ]
    ]);

    $response->assertSessionHasErrors();
});
