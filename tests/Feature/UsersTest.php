<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeUsersAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from users list', function (): void {
    get('/admin/users')->assertRedirect('/admin/login');
});

it('redirects guests away from create user page', function (): void {
    get('/admin/users/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a user', function (): void {
    actingAs(makeUsersAdminUser());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'password' => '',
            'roles' => [],
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
});

it('prevents duplicate user email at validation level', function (): void {
    actingAs(makeUsersAdminUser());

    User::query()->create([
        'name' => 'Existing User',
        'email' => 'existing.user@example.com',
        'password' => 'password123',
    ]);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Duplicate Email User',
            'email' => 'existing.user@example.com',
            'password' => 'password123',
            'roles' => [],
        ])
        ->call('create')
        ->assertHasFormErrors([
            'email' => 'unique',
        ]);
});

it('performs user crud operations', function (): void {
    actingAs(makeUsersAdminUser());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'CRUD User',
            'email' => 'crud.user@example.com',
            'password' => 'password123',
            'roles' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = User::query()->where('email', 'crud.user@example.com')->firstOrFail();

    Livewire::test(ListUsers::class)
        ->assertSuccessful()
        ->assertSee('CRUD User')
        ->assertSee('crud.user@example.com');

    Livewire::test(EditUser::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'CRUD User Updated',
            'email' => 'crud.user.updated@example.com',
            'roles' => [],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('CRUD User Updated');
    expect($record->email)->toBe('crud.user.updated@example.com');

    Livewire::test(EditUser::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(User::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
