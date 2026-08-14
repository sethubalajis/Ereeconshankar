<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdminUser(): User
    {
        $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $user = User::factory()->create(['email' => 'admin@example.com', 'password' => 'password']);
        $user->assignRole($role);
        return $user;
    }

    public function test_admin_login_page_loads(): void
    {
        $this->get('/admin/login')->assertOk();
    }

    public function test_admin_login_succeeds_with_valid_credentials(): void
    {
        $user = $this->makeAdminUser();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'password',
                'remember' => false,
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin');

        $this->assertAuthenticated();
        $this->assertSame($user->getKey(), auth()->id());
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $user = $this->makeAdminUser();

        Livewire::test(Login::class)
            ->fillForm([
                'email' => $user->email,
                'password' => 'WrongPassword123!',
                'remember' => false,
            ])
            ->call('authenticate')
            ->assertHasFormErrors(['email']);

        $this->assertGuest();
    }
}
