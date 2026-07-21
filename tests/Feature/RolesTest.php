<?php

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeRolesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from roles list', function (): void {
    get('/admin/roles')->assertRedirect('/admin/login');
});

it('redirects guests away from create role page', function (): void {
    get('/admin/roles/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a role', function (): void {
    actingAs(makeRolesAdminUser());

    Livewire::test(CreateRole::class)
        ->fillForm([
            'name' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
        ]);
});

it('performs role crud operations', function (): void {
    actingAs(makeRolesAdminUser());

    Livewire::test(CreateRole::class)
        ->fillForm([
            'name' => 'Accounts Reviewer',
            'description' => 'Can review accounts data',
            'status' => true,
            'guard_name' => 'web',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Role::query()->where('name', 'Accounts Reviewer')->firstOrFail();

    Livewire::test(ListRoles::class)
        ->assertSuccessful()
        ->assertSee('Accounts Reviewer');

    Livewire::test(EditRole::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Accounts Approver',
            'description' => 'Can approve accounts data',
            'status' => true,
            'guard_name' => 'web',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Accounts Approver');

    Livewire::test(EditRole::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Role::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
