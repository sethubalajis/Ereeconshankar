<?php

use App\Filament\Resources\LoyaltyPoints\Pages\CreateLoyaltyPoint;
use App\Filament\Resources\LoyaltyPoints\Pages\EditLoyaltyPoint;
use App\Filament\Resources\LoyaltyPoints\Pages\ListLoyaltyPoints;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\LoyaltyPoint;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLoyaltyPointsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createLoyaltyPointCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Loyalty Point Group', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create([
        'customer_group_id' => $group->getKey(),
        'name' => 'Points Customer',
        'phone' => '9000000011',
        'total_points' => 0,
        'wallet_balance' => 0,
        'status' => true,
    ]);
}

it('redirects guests away from loyalty points list', function (): void { get('/admin/loyalty-points')->assertRedirect('/admin/login'); });
it('redirects guests away from create loyalty point page', function (): void { get('/admin/loyalty-points/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a loyalty point', function (): void {
    actingAs(makeLoyaltyPointsAdminUser());

    Livewire::test(CreateLoyaltyPoint::class)
        ->fillForm(['customer_id' => null, 'transaction_type' => null, 'points' => null, 'description' => '', 'transaction_date' => null])
        ->call('create')
        ->assertHasFormErrors(['customer_id' => 'required', 'transaction_type' => 'required', 'points' => 'required', 'transaction_date' => 'required']);
});

it('performs loyalty point crud operations', function (): void {
    actingAs(makeLoyaltyPointsAdminUser());
    $customer = createLoyaltyPointCustomer();

    Livewire::test(CreateLoyaltyPoint::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'transaction_type' => 'earn', 'points' => 150, 'description' => 'Signup bonus', 'transaction_date' => '2026-07-21'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = LoyaltyPoint::query()->firstOrFail();
    Livewire::test(ListLoyaltyPoints::class)->assertSuccessful()->assertSee('Signup bonus');

    Livewire::test(EditLoyaltyPoint::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'transaction_type' => 'redeem', 'points' => 100, 'description' => 'Redeemed at checkout', 'transaction_date' => '2026-07-22'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->transaction_type)->toBe('redeem');

    Livewire::test(EditLoyaltyPoint::class, ['record' => $record->getKey()])->callAction('delete');
    expect(LoyaltyPoint::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
