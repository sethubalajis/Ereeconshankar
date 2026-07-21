<?php

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Models\Coupon;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCouponsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from coupons list', function (): void { get('/admin/coupons')->assertRedirect('/admin/login'); });
it('redirects guests away from create coupon page', function (): void { get('/admin/coupons/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a coupon', function (): void {
    actingAs(makeCouponsAdminUser());
    Livewire::test(CreateCoupon::class)
        ->fillForm(['code' => '', 'discount_percentage' => null, 'discount_amount' => null, 'start_date' => null, 'end_date' => null, 'min_purchase_amount' => null, 'max_usage' => null, 'current_usage' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['code' => 'required', 'min_purchase_amount' => 'required', 'max_usage' => 'required', 'current_usage' => 'required', 'status' => 'required']);
});

it('performs coupon crud operations', function (): void {
    actingAs(makeCouponsAdminUser());

    Livewire::test(CreateCoupon::class)
        ->fillForm(['code' => 'SAVE10', 'discount_percentage' => 10, 'discount_amount' => null, 'start_date' => '2026-07-21', 'end_date' => '2026-08-21', 'min_purchase_amount' => 100, 'max_usage' => 5, 'current_usage' => 0, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Coupon::query()->firstOrFail();
    Livewire::test(ListCoupons::class)->assertSuccessful()->assertSee('SAVE10');

    Livewire::test(EditCoupon::class, ['record' => $record->getKey()])
        ->fillForm(['code' => 'SAVE20', 'discount_percentage' => 20, 'discount_amount' => null, 'start_date' => '2026-07-22', 'end_date' => '2026-08-22', 'min_purchase_amount' => 200, 'max_usage' => 10, 'current_usage' => 1, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('SAVE20');

    Livewire::test(EditCoupon::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Coupon::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
