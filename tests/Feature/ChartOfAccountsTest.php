<?php

use App\Filament\Resources\ChartOfAccounts\Pages\CreateChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\EditChartOfAccount;
use App\Filament\Resources\ChartOfAccounts\Pages\ListChartOfAccounts;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeChartOfAccountsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createChartOfAccountGroup(): AccountGroup
{
    return AccountGroup::query()->create(['name' => 'Assets', 'type' => 'asset']);
}

it('redirects guests away from chart of accounts list', function (): void { get('/admin/chart-of-accounts')->assertRedirect('/admin/login'); });
it('redirects guests away from create chart of account page', function (): void { get('/admin/chart-of-accounts/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a chart of account', function (): void {
    actingAs(makeChartOfAccountsAdminUser());
    Livewire::test(CreateChartOfAccount::class)
        ->fillForm(['account_group_id' => null, 'account_name' => '', 'account_type' => null, 'opening_balance' => null, 'current_balance' => null])
        ->call('create')
        ->assertHasFormErrors(['account_group_id' => 'required', 'account_name' => 'required', 'account_type' => 'required', 'opening_balance' => 'required', 'current_balance' => 'required']);
});

it('performs chart of account crud operations', function (): void {
    actingAs(makeChartOfAccountsAdminUser());
    $group = createChartOfAccountGroup();

    Livewire::test(CreateChartOfAccount::class)
        ->fillForm(['account_group_id' => $group->getKey(), 'account_name' => 'Cash in Hand', 'account_type' => 'asset', 'opening_balance' => 1000, 'current_balance' => 1200])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ChartOfAccount::query()->firstOrFail();
    Livewire::test(ListChartOfAccounts::class)->assertSuccessful()->assertSee('Cash in Hand');

    Livewire::test(EditChartOfAccount::class, ['record' => $record->getKey()])
        ->fillForm(['account_group_id' => $group->getKey(), 'account_name' => 'Cash at Bank', 'account_type' => 'asset', 'opening_balance' => 1500, 'current_balance' => 1800])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->account_name)->toBe('Cash at Bank');

    Livewire::test(EditChartOfAccount::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ChartOfAccount::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
