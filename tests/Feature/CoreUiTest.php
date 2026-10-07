<?php

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

if (extension_loaded('pdo_sqlite')) {
    uses(RefreshDatabase::class);
}

test('login and registration pages render from the auth views', function () {
    $this->withoutVite();
    $this->get('/login')->assertOk();
    $this->get('/register')->assertOk();
});

test('core general ledger pages render for authenticated users', function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The PDO SQLite driver is required for database-backed feature tests.');
    }

    $this->seed(RoleAndPermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Admin');
    $this->actingAs($this->user);

    foreach ([
        '/',
        '/journals',
        '/journals/create',
        '/accounts',
        '/accounts/create',
        '/users',
        '/reports/general-ledger',
        '/reports/profit-loss',
        '/reports/balance-sheet',
    ] as $path) {
        $this->get($path)->assertOk();
    }
});

test('journal entry form posts a balanced entry using the migrated schema', function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The PDO SQLite driver is required for database-backed feature tests.');
    }

    $this->seed(RoleAndPermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Admin');
    $this->actingAs($this->user);

    $branch = Branch::create([
        'code' => 'HQ',
        'name' => 'Kantor Pusat',
        'is_active' => true,
    ]);
    $this->user->update(['branch_id' => $branch->id]);

    $cash = ChartOfAccount::create([
        'code' => '1101',
        'name' => 'Kas',
        'type' => 'asset',
        'normal_balance' => 'debit',
        'is_active' => true,
    ]);
    $capital = ChartOfAccount::create([
        'code' => '3101',
        'name' => 'Modal',
        'type' => 'equity',
        'normal_balance' => 'credit',
        'is_active' => true,
    ]);

    $response = $this->post(route('journals.store'), [
        'branch_id' => $branch->id,
        'transaction_date' => '2026-10-07',
        'description' => 'Setoran modal',
        'items' => [
            ['account_id' => $cash->id, 'debit' => '1000000', 'credit' => '0'],
            ['account_id' => $capital->id, 'debit' => '0', 'credit' => '1000000'],
        ],
    ]);

    $journal = Journal::withoutBranchScope()->firstOrFail();

    $response->assertRedirect(route('journals.show', $journal));
    $this->assertDatabaseHas('journal_entries', [
        'id' => $journal->id,
        'date' => '2026-10-07',
        'status' => 'posted',
        'created_by' => $this->user->id,
    ]);
    $this->assertDatabaseHas('journal_items', [
        'journal_entry_id' => $journal->id,
        'account_id' => $cash->id,
        'debit' => '1000000.00',
    ]);

    $this->get(route('journals.index', ['start_date' => '2026-10-01']))
        ->assertOk();

    $this->get(route('reports.general-ledger', [
        'branch_id' => $branch->id,
        'account_id' => $cash->id,
        'start_date' => '2026-10-07',
        'end_date' => '2026-10-07',
    ]))->assertOk();
});
