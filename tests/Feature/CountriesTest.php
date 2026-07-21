<?php

use App\Filament\Resources\Countries\Pages\CreateCountry;
use App\Filament\Resources\Countries\Pages\EditCountry;
use App\Filament\Resources\Countries\Pages\ListCountries;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCountryAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from countries list', function (): void {
    get('/admin/countries')->assertRedirect('/admin/login');
});

it('redirects guests away from create country page', function (): void {
    get('/admin/countries/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a country', function (): void {
    actingAs(makeCountryAdminUser());

    Livewire::test(CreateCountry::class)
        ->fillForm([
            'name' => '',
            'code' => '',
            'phone_code' => '',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'code' => 'required',
        ]);
});

it('performs country crud operations', function (): void {
    actingAs(makeCountryAdminUser());

    Livewire::test(CreateCountry::class)
        ->fillForm([
            'name' => 'India',
            'code' => 'IND',
            'phone_code' => '+91',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Country::query()->where('code', 'IND')->firstOrFail();

    Livewire::test(ListCountries::class)
        ->assertSuccessful()
        ->assertSee('India')
        ->assertSee('IND');

    Livewire::test(EditCountry::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'United States',
            'code' => 'USA',
            'phone_code' => '+1',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('United States');
    expect($record->code)->toBe('USA');
    expect((bool) $record->status)->toBeFalse();

    Livewire::test(EditCountry::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(Country::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
