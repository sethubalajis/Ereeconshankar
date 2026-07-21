<?php

use App\Filament\Resources\FestivalOffers\Pages\CreateFestivalOffer;
use App\Filament\Resources\FestivalOffers\Pages\EditFestivalOffer;
use App\Filament\Resources\FestivalOffers\Pages\ListFestivalOffers;
use App\Models\FestivalOffer;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeFestivalOffersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from festival offers list', function (): void { get('/admin/festival-offers')->assertRedirect('/admin/login'); });
it('redirects guests away from create festival offer page', function (): void { get('/admin/festival-offers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a festival offer', function (): void {
    actingAs(makeFestivalOffersAdminUser());
    Livewire::test(CreateFestivalOffer::class)
        ->fillForm(['name' => '', 'start_date' => null, 'end_date' => null, 'discount_percentage' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'start_date' => 'required', 'end_date' => 'required', 'discount_percentage' => 'required', 'status' => 'required']);
});

it('performs festival offer crud operations', function (): void {
    actingAs(makeFestivalOffersAdminUser());

    Livewire::test(CreateFestivalOffer::class)
        ->fillForm(['name' => 'Diwali Blast', 'start_date' => '2026-10-20', 'end_date' => '2026-11-05', 'discount_percentage' => 12, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = FestivalOffer::query()->firstOrFail();
    Livewire::test(ListFestivalOffers::class)->assertSuccessful()->assertSee('Diwali Blast');

    Livewire::test(EditFestivalOffer::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Pongal Special', 'start_date' => '2027-01-10', 'end_date' => '2027-01-20', 'discount_percentage' => 15, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Pongal Special');

    Livewire::test(EditFestivalOffer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(FestivalOffer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
