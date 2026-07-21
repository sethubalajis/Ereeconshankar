<?php

use App\Filament\Resources\PromotionTypes\Pages\CreatePromotionType;
use App\Filament\Resources\PromotionTypes\Pages\EditPromotionType;
use App\Filament\Resources\PromotionTypes\Pages\ListPromotionTypes;
use App\Models\PromotionType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePromotionTypesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from promotion types list', function (): void {
    get('/admin/promotion-types')->assertRedirect('/admin/login');
});

it('redirects guests away from create promotion type page', function (): void {
    get('/admin/promotion-types/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a promotion type', function (): void {
    actingAs(makePromotionTypesAdminUser());

    Livewire::test(CreatePromotionType::class)
        ->fillForm(['name' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

it('performs promotion type crud operations', function (): void {
    actingAs(makePromotionTypesAdminUser());

    Livewire::test(CreatePromotionType::class)
        ->fillForm([
            'name' => 'Seasonal Offer',
            'description' => 'Seasonal promotion type',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PromotionType::query()->firstOrFail();

    Livewire::test(ListPromotionTypes::class)
        ->assertSuccessful()
        ->assertSee('Seasonal Offer');

    Livewire::test(EditPromotionType::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Festival Offer',
            'description' => 'Festival promotion type',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Festival Offer');

    Livewire::test(EditPromotionType::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PromotionType::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
