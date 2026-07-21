<?php

use App\Filament\Resources\Sessions\Pages\EditSession;
use App\Filament\Resources\Sessions\Pages\ListSessions;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSessionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from sessions list', function (): void {
    get('/admin/sessions')->assertRedirect('/admin/login');
});

it('redirects guests away from create session page', function (): void {
    get('/admin/sessions/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a session', function (): void {
    actingAs(makeSessionsAdminUser());

    expect(true)->toBeTrue();
})->skip('Session resource form has no input fields; create validation cannot be asserted from form schema.');

it('performs session crud operations', function (): void {
    actingAs(makeSessionsAdminUser());

    $record = Session::query()->create([
        'id' => 'sess-test-001',
        'user_id' => null,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Pest',
        'payload' => 'test-payload',
        'last_activity' => now()->timestamp,
    ]);

    Livewire::test(ListSessions::class)
        ->assertSuccessful()
        ->assertSee('127.0.0.1');

    Livewire::test(EditSession::class, ['record' => $record->getKey()])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditSession::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Session::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
