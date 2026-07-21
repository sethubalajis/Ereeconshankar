<?php

use App\Filament\Resources\TrialBalances\Pages\CreateTrialBalance;
use App\Filament\Resources\TrialBalances\Pages\EditTrialBalance;
use App\Filament\Resources\TrialBalances\Pages\ListTrialBalances;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\Role;
use App\Models\TrialBalance;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTrialBalancesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTrialBalanceAccount(): ChartOfAccount
{
    $group = AccountGroup::query()->create(['name' => 'Assets', 'code' => 'AG-ASSET', 'type' => 'asset']);

    return ChartOfAccount::query()->create([
        'account_group_id' => $group->getKey(),
        'account_code' => '1001',
        'account_name' => 'Cash Account',
        'account_type' => 'asset',
        'opening_balance' => 1000,
        'current_balance' => 1000,
    ]);
}

it('redirects guests away from trial balances list', function (): void {
    get('/admin/trial-balances')->assertRedirect('/admin/login');
});

it('redirects guests away from create trial balance page', function (): void {
    get('/admin/trial-balances/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a trial balance', function (): void {
    actingAs(makeTrialBalancesAdminUser());

    Livewire::test(CreateTrialBalance::class)
        ->fillForm([
            'as_of_date' => null,
            'chart_of_account_id' => null,
            'debit_balance' => null,
            'credit_balance' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'as_of_date' => 'required',
            'chart_of_account_id' => 'required',
            'debit_balance' => 'required',
            'credit_balance' => 'required',
        ]);
});

it('performs trial balance crud operations', function (): void {
    actingAs(makeTrialBalancesAdminUser());
    $account = createTrialBalanceAccount();

    Livewire::test(CreateTrialBalance::class)
        ->fillForm([
            'as_of_date' => '2026-07-21',
            'chart_of_account_id' => $account->getKey(),
            'debit_balance' => 5000,
            'credit_balance' => 5000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TrialBalance::query()->firstOrFail();
    Livewire::test(ListTrialBalances::class)->assertSuccessful()->assertSee('Cash Account');

    Livewire::test(EditTrialBalance::class, ['record' => $record->getKey()])
        ->fillForm([
            'as_of_date' => '2026-07-22',
            'chart_of_account_id' => $account->getKey(),
            'debit_balance' => 6000,
            'credit_balance' => 6000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->debit_balance)->toBe(6000.0);

    Livewire::test(EditTrialBalance::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TrialBalance::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
