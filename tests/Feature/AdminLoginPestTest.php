<?php

use App\Models\Role;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Livewire\Livewire;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAdminLoginUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'password']);
    $user->assignRole($role);
    return $user;
}

it('loads the admin login page', function (): void {
    get('/admin/login')->assertOk();
});

it('allows a valid user to log in from admin login', function (): void {
    $user = makeAdminLoginUser();

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
            'remember' => false,
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect('/admin');

    expect(auth()->check())->toBeTrue();
    expect(auth()->id())->toBe($user->getKey());
});

it('rejects invalid credentials from admin login', function (): void {
    $user = makeAdminLoginUser();

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'WrongPassword123!',
            'remember' => false,
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);

    expect(auth()->check())->toBeFalse();
});
