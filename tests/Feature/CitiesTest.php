<?php

use App\Filament\Resources\Cities\Pages\CreateCity;
use App\Filament\Resources\Cities\Pages\EditCity;
use App\Filament\Resources\Cities\Pages\ListCities;
use App\Models\City;
use App\Models\Country;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCityAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createCityTestState(): State
{
    $country = Country::query()->create([
        'name' => 'City Test Country',
        'code' => 'CTC',
        'phone_code' => '+00',
        'status' => true,
    ]);

    return State::query()->create([
        'country_id' => $country->getKey(),
        'name' => 'City Test State',
        'code' => 'CTS',
        'status' => true,
    ]);
}

it('redirects guests away from cities list', function (): void {
    get('/admin/cities')->assertRedirect('/admin/login');
});

it('redirects guests away from create city page', function (): void {
    get('/admin/cities/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a city', function (): void {
    actingAs(makeCityAdminUser());

    Livewire::test(CreateCity::class)
        ->fillForm([
            'state_id' => null,
            'name' => '',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'state_id' => 'required',
            'name' => 'required',
        ]);
});

it('performs city crud operations', function (): void {
    actingAs(makeCityAdminUser());

    $state = createCityTestState();

    Livewire::test(CreateCity::class)
        ->fillForm([
            'state_id' => $state->getKey(),
            'name' => 'Chennai',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = City::query()->where('name', 'Chennai')->firstOrFail();

    Livewire::test(ListCities::class)
        ->assertSuccessful()
        ->assertSee('Chennai');

    Livewire::test(EditCity::class, ['record' => $record->getKey()])
        ->fillForm([
            'state_id' => $state->getKey(),
            'name' => 'Bengaluru',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Bengaluru');
    expect((bool) $record->status)->toBeFalse();

    Livewire::test(EditCity::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(City::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
