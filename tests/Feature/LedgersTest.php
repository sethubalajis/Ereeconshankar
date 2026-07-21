<?php

use App\Filament\Resources\Ledgers\Pages\CreateLedger;
use App\Filament\Resources\Ledgers\Pages\EditLedger;
use App\Filament\Resources\Ledgers\Pages\ListLedgers;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\Ledger;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLedgersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createLedgerAccount(): ChartOfAccount
{
    $group = AccountGroup::query()->create(['name' => 'Ledger Group', 'type' => 'asset']);
    return ChartOfAccount::query()->create([
        'account_group_id' => $group->getKey(),
        'account_name' => 'Ledger Account',
        'account_type' => 'asset',
        'opening_balance' => 0,
        'current_balance' => 0,
    ]);
}

it('redirects guests away from ledgers list', function (): void { get('/admin/ledgers')->assertRedirect('/admin/login'); });
it('redirects guests away from create ledger page', function (): void { get('/admin/ledgers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a ledger', function (): void {
    actingAs(makeLedgersAdminUser());

    Livewire::test(CreateLedger::class)
        ->fillForm(['chart_of_account_id' => null, 'transaction_date' => null, 'description' => '', 'debit' => null, 'credit' => null, 'balance' => null])
        ->call('create')
        ->assertHasFormErrors(['chart_of_account_id' => 'required', 'transaction_date' => 'required', 'debit' => 'required', 'credit' => 'required', 'balance' => 'required']);
});

it('performs ledger crud operations', function (): void {
    actingAs(makeLedgersAdminUser());
    $account = createLedgerAccount();

    Livewire::test(CreateLedger::class)
        ->fillForm(['chart_of_account_id' => $account->getKey(), 'transaction_date' => '2026-07-21', 'description' => 'Ledger open', 'debit' => 2000, 'credit' => 0, 'balance' => 2000])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Ledger::query()->firstOrFail();
    Livewire::test(ListLedgers::class)->assertSuccessful()->assertSee('Ledger open');

    Livewire::test(EditLedger::class, ['record' => $record->getKey()])
        ->fillForm(['chart_of_account_id' => $account->getKey(), 'transaction_date' => '2026-07-22', 'description' => 'Ledger close', 'debit' => 0, 'credit' => 1000, 'balance' => 1000])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->description)->toBe('Ledger close');

    Livewire::test(EditLedger::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Ledger::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
