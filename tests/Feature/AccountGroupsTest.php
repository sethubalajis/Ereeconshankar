<?php

use App\Filament\Resources\AccountGroups\Pages\CreateAccountGroup;
use App\Filament\Resources\AccountGroups\Pages\EditAccountGroup;
use App\Filament\Resources\AccountGroups\Pages\ListAccountGroups;
use App\Models\AccountGroup;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAccountGroupsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from account groups list', function (): void {
    get('/admin/account-groups')->assertRedirect('/admin/login');
});

it('redirects guests away from create account group page', function (): void {
    get('/admin/account-groups/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating an account group', function (): void {
    actingAs(makeAccountGroupsAdminUser());

    Livewire::test(CreateAccountGroup::class)
        ->fillForm(['name' => '', 'type' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'type' => 'required']);
});

it('performs account group crud operations', function (): void {
    actingAs(makeAccountGroupsAdminUser());

    Livewire::test(CreateAccountGroup::class)
        ->fillForm(['name' => 'Current Assets', 'parent_id' => null, 'type' => 'asset'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = AccountGroup::query()->firstOrFail();
    Livewire::test(ListAccountGroups::class)->assertSuccessful()->assertSee('Current Assets');

    Livewire::test(EditAccountGroup::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Fixed Assets', 'parent_id' => null, 'type' => 'asset'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Fixed Assets');

    Livewire::test(EditAccountGroup::class, ['record' => $record->getKey()])->callAction('delete');
    expect(AccountGroup::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
