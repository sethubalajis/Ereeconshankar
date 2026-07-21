<?php

use App\Filament\Resources\States\Pages\CreateState;
use App\Filament\Resources\States\Pages\EditState;
use App\Filament\Resources\States\Pages\ListStates;
use App\Models\Country;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStateAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStateTestCountry(): Country
{
    return Country::query()->create([
        'name' => 'State Test Country',
        'code' => 'STC',
        'phone_code' => '+00',
        'status' => true,
    ]);
}

it('redirects guests away from states list', function (): void {
    get('/admin/states')->assertRedirect('/admin/login');
});

it('redirects guests away from create state page', function (): void {
    get('/admin/states/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a state', function (): void {
    actingAs(makeStateAdminUser());

    Livewire::test(CreateState::class)
        ->fillForm([
            'country_id' => null,
            'name' => '',
            'code' => '',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'country_id' => 'required',
            'name' => 'required',
        ]);
});

it('performs state crud operations', function (): void {
    actingAs(makeStateAdminUser());

    $country = createStateTestCountry();

    Livewire::test(CreateState::class)
        ->fillForm([
            'country_id' => $country->getKey(),
            'name' => 'Tamil Nadu',
            'code' => 'TN',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = State::query()->where('name', 'Tamil Nadu')->firstOrFail();

    Livewire::test(ListStates::class)
        ->assertSuccessful()
        ->assertSee('Tamil Nadu');

    Livewire::test(EditState::class, ['record' => $record->getKey()])
        ->fillForm([
            'country_id' => $country->getKey(),
            'name' => 'Karnataka',
            'code' => 'KA',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Karnataka');
    expect($record->code)->toBe('KA');
    expect((bool) $record->status)->toBeFalse();

    Livewire::test(EditState::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(State::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
