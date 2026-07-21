<?php

use App\Filament\Resources\ContraEntries\Pages\CreateContraEntry;
use App\Filament\Resources\ContraEntries\Pages\EditContraEntry;
use App\Filament\Resources\ContraEntries\Pages\ListContraEntries;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\ContraEntry;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeContraEntriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createContraEntryAccount(string $name): ChartOfAccount
{
    $group = AccountGroup::query()->create(['name' => $name . ' Group', 'type' => 'asset']);
    return ChartOfAccount::query()->create(['account_group_id' => $group->getKey(), 'account_name' => $name, 'account_type' => 'asset', 'opening_balance' => 0, 'current_balance' => 0]);
}

it('redirects guests away from contra entries list', function (): void { get('/admin/contra-entries')->assertRedirect('/admin/login'); });
it('redirects guests away from create contra entry page', function (): void { get('/admin/contra-entries/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a contra entry', function (): void {
    actingAs(makeContraEntriesAdminUser());
    Livewire::test(CreateContraEntry::class)
        ->fillForm(['date' => null, 'from_account_id' => null, 'to_account_id' => null, 'amount' => null, 'description' => ''])
        ->call('create')
        ->assertHasFormErrors(['date' => 'required', 'from_account_id' => 'required', 'to_account_id' => 'required', 'amount' => 'required']);
});

it('performs contra entry crud operations', function (): void {
    actingAs(makeContraEntriesAdminUser());
    $fromAccount = createContraEntryAccount('Cash');
    $toAccount = createContraEntryAccount('Bank');

    Livewire::test(CreateContraEntry::class)
        ->fillForm(['date' => '2026-07-21', 'from_account_id' => $fromAccount->getKey(), 'to_account_id' => $toAccount->getKey(), 'amount' => 5000, 'description' => 'Cash deposit'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ContraEntry::query()->firstOrFail();
    Livewire::test(ListContraEntries::class)->assertSuccessful()->assertSee('Cash deposit');

    Livewire::test(EditContraEntry::class, ['record' => $record->getKey()])
        ->fillForm(['date' => '2026-07-22', 'from_account_id' => $fromAccount->getKey(), 'to_account_id' => $toAccount->getKey(), 'amount' => 6000, 'description' => 'Cash transfer'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(6000.0);

    Livewire::test(EditContraEntry::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ContraEntry::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
