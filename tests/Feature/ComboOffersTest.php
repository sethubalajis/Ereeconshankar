<?php

use App\Filament\Resources\ComboOffers\Pages\CreateComboOffer;
use App\Filament\Resources\ComboOffers\Pages\EditComboOffer;
use App\Filament\Resources\ComboOffers\Pages\ListComboOffers;
use App\Models\ComboOffer;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeComboOffersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from combo offers list', function (): void { get('/admin/combo-offers')->assertRedirect('/admin/login'); });
it('redirects guests away from create combo offer page', function (): void { get('/admin/combo-offers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a combo offer', function (): void {
    actingAs(makeComboOffersAdminUser());
    Livewire::test(CreateComboOffer::class)
        ->fillForm(['name' => '', 'code' => '', 'total_price' => null, 'start_date' => null, 'end_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'total_price' => 'required', 'status' => 'required']);
});

it('performs combo offer crud operations', function (): void {
    actingAs(makeComboOffersAdminUser());

    Livewire::test(CreateComboOffer::class)
        ->fillForm(['name' => 'Festival Combo', 'total_price' => 999, 'start_date' => '2026-07-21', 'end_date' => '2026-08-21', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ComboOffer::query()->firstOrFail();
    Livewire::test(ListComboOffers::class)->assertSuccessful()->assertSee('Festival Combo');

    Livewire::test(EditComboOffer::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Mega Combo', 'total_price' => 1099, 'start_date' => '2026-07-22', 'end_date' => '2026-08-22', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Mega Combo');

    Livewire::test(EditComboOffer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ComboOffer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
