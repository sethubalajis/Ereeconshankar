<?php

use App\Filament\Resources\PromoCodes\Pages\CreatePromoCode;
use App\Filament\Resources\PromoCodes\Pages\EditPromoCode;
use App\Filament\Resources\PromoCodes\Pages\ListPromoCodes;
use App\Models\PromoCode;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePromoCodesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from promo codes list', function (): void {
    get('/admin/promo-codes')->assertRedirect('/admin/login');
});

it('redirects guests away from create promo code page', function (): void {
    get('/admin/promo-codes/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a promo code', function (): void {
    actingAs(makePromoCodesAdminUser());

    Livewire::test(CreatePromoCode::class)
        ->fillForm(['status' => null])
        ->call('create')
        ->assertHasFormErrors(['status' => 'required']);
});

it('performs promo code crud operations', function (): void {
    actingAs(makePromoCodesAdminUser());

    Livewire::test(CreatePromoCode::class)
        ->fillForm([
            'discount_percentage' => 10,
            'discount_amount' => 100,
            'valid_from' => '2026-07-21',
            'valid_to' => '2026-08-21',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PromoCode::query()->firstOrFail();

    Livewire::test(ListPromoCodes::class)
        ->assertSuccessful()
        ->assertSee($record->code);

    Livewire::test(EditPromoCode::class, ['record' => $record->getKey()])
        ->fillForm([
            'discount_percentage' => 12,
            'discount_amount' => 150,
            'valid_from' => '2026-07-22',
            'valid_to' => '2026-08-31',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->discount_percentage)->toBe(12.0);

    Livewire::test(EditPromoCode::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PromoCode::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
