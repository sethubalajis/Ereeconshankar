<?php

use App\Filament\Resources\Seasons\Pages\CreateSeason;
use App\Filament\Resources\Seasons\Pages\EditSeason;
use App\Filament\Resources\Seasons\Pages\ListSeasons;
use App\Models\Role;
use App\Models\Season;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSeasonsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from seasons list', function (): void {
    get('/admin/seasons')->assertRedirect('/admin/login');
});

it('redirects guests away from create season page', function (): void {
    get('/admin/seasons/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a season', function (): void {
    actingAs(makeSeasonsAdminUser());

    Livewire::test(CreateSeason::class)
        ->fillForm([
            'name' => '',
            'code' => '',
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'code' => 'required',
            'status' => 'required',
        ]);
});

it('performs season crud operations', function (): void {
    actingAs(makeSeasonsAdminUser());

    Livewire::test(CreateSeason::class)
        ->fillForm([
            'name' => 'Monsoon',
            'code' => 'SEA-MON',
            'start_date' => '2026-06-01',
            'end_date' => '2026-09-30',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Season::query()->firstOrFail();

    Livewire::test(ListSeasons::class)
        ->assertSuccessful()
        ->assertSee('Monsoon');

    Livewire::test(EditSeason::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Winter',
            'code' => 'SEA-WIN',
            'start_date' => '2026-10-01',
            'end_date' => '2027-01-31',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('SEA-WIN');

    Livewire::test(EditSeason::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Season::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
