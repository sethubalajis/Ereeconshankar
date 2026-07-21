<?php

use App\Filament\Resources\LoginHistories\Pages\CreateLoginHistory;
use App\Filament\Resources\LoginHistories\Pages\EditLoginHistory;
use App\Filament\Resources\LoginHistories\Pages\ListLoginHistories;
use App\Models\LoginHistory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLoginHistoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from login histories list', function (): void { get('/admin/login-histories')->assertRedirect('/admin/login'); });
it('redirects guests away from create login history page', function (): void { get('/admin/login-histories/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a login history', function (): void {
    actingAs(makeLoginHistoriesAdminUser());

    Livewire::test(CreateLoginHistory::class)
        ->fillForm(['user_id' => null, 'ip_address' => '', 'user_agent' => '', 'login_at' => null, 'logout_at' => null])
        ->call('create')
        ->assertHasFormErrors(['user_id' => 'required']);
});

it('performs login history crud operations', function (): void {
    actingAs(makeLoginHistoriesAdminUser());
    $loginUser = User::factory()->create(['name' => 'Audit User']);

    Livewire::test(CreateLoginHistory::class)
        ->fillForm(['user_id' => $loginUser->getKey(), 'ip_address' => '127.0.0.1', 'user_agent' => 'Pest Browser', 'login_at' => '2026-07-21 09:30:00', 'logout_at' => '2026-07-21 10:00:00'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = LoginHistory::query()->firstOrFail();
    Livewire::test(ListLoginHistories::class)->assertSuccessful()->assertSee('127.0.0.1');

    Livewire::test(EditLoginHistory::class, ['record' => $record->getKey()])
        ->fillForm(['user_id' => $loginUser->getKey(), 'ip_address' => '10.0.0.8', 'user_agent' => 'Updated Agent', 'login_at' => '2026-07-21 11:00:00', 'logout_at' => '2026-07-21 11:30:00'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->ip_address)->toBe('10.0.0.8');

    Livewire::test(EditLoginHistory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(LoginHistory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
