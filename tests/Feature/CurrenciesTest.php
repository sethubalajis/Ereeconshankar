<?php

use App\Filament\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Resources\Currencies\Pages\ListCurrencies;
use App\Models\Currency;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCurrencyAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from currencies list', function (): void {
    get('/admin/currencies')->assertRedirect('/admin/login');
});

it('redirects guests away from create currency page', function (): void {
    get('/admin/currencies/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a currency', function (): void {
    actingAs(makeCurrencyAdminUser());

    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'name' => '',
            'code' => '',
            'symbol' => '',
            'exchange_rate' => null,
            'is_default' => false,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'code' => 'required',
            'exchange_rate' => 'required',
        ]);
});

it('performs currency crud operations', function (): void {
    actingAs(makeCurrencyAdminUser());

    Livewire::test(CreateCurrency::class)
        ->fillForm([
            'name' => 'Indian Rupee',
            'code' => 'INR',
            'symbol' => 'Rs',
            'exchange_rate' => 1,
            'is_default' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Currency::query()->where('code', 'INR')->firstOrFail();

    Livewire::test(ListCurrencies::class)
        ->assertSuccessful()
        ->assertSee('Indian Rupee')
        ->assertSee('INR');

    Livewire::test(EditCurrency::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 83.25,
            'is_default' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('US Dollar');
    expect($record->code)->toBe('USD');
    expect((float) $record->exchange_rate)->toBe(83.25);

    Livewire::test(EditCurrency::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(Currency::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
