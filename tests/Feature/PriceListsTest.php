<?php

use App\Filament\Resources\PriceLists\Pages\CreatePriceList;
use App\Filament\Resources\PriceLists\Pages\EditPriceList;
use App\Filament\Resources\PriceLists\Pages\ListPriceLists;
use App\Models\PriceList;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePriceListsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from price lists list', function (): void { get('/admin/price-lists')->assertRedirect('/admin/login'); });
it('redirects guests away from create price list page', function (): void { get('/admin/price-lists/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a price list', function (): void {
    actingAs(makePriceListsAdminUser());

    Livewire::test(CreatePriceList::class)
        ->fillForm(['name' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs price list crud operations', function (): void {
    actingAs(makePriceListsAdminUser());

    Livewire::test(CreatePriceList::class)
        ->fillForm(['name' => 'Retail Price List', 'valid_from' => '2026-07-01', 'valid_to' => '2026-12-31', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PriceList::query()->firstOrFail();
    Livewire::test(ListPriceLists::class)->assertSuccessful()->assertSee('Retail Price List');

    Livewire::test(EditPriceList::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Wholesale Price List', 'valid_from' => '2026-08-01', 'valid_to' => '2027-01-31', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Wholesale Price List');

    Livewire::test(EditPriceList::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PriceList::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
