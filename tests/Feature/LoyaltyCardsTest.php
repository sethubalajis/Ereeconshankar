<?php

use App\Filament\Resources\LoyaltyCards\Pages\CreateLoyaltyCard;
use App\Filament\Resources\LoyaltyCards\Pages\EditLoyaltyCard;
use App\Filament\Resources\LoyaltyCards\Pages\ListLoyaltyCards;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\LoyaltyCard;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLoyaltyCardsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createLoyaltyCardCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Loyalty Group', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create([
        'customer_group_id' => $group->getKey(),
        'name' => 'Loyalty Customer',
        'phone' => '9000000010',
        'total_points' => 0,
        'wallet_balance' => 0,
        'status' => true,
    ]);
}

it('redirects guests away from loyalty cards list', function (): void { get('/admin/loyalty-cards')->assertRedirect('/admin/login'); });
it('redirects guests away from create loyalty card page', function (): void { get('/admin/loyalty-cards/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a loyalty card', function (): void {
    actingAs(makeLoyaltyCardsAdminUser());

    Livewire::test(CreateLoyaltyCard::class)
        ->fillForm(['customer_id' => null, 'card_number' => '', 'issue_date' => null, 'expiry_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['customer_id' => 'required', 'card_number' => 'required', 'issue_date' => 'required', 'status' => 'required']);
});

it('performs loyalty card crud operations', function (): void {
    actingAs(makeLoyaltyCardsAdminUser());
    $customer = createLoyaltyCardCustomer();

    Livewire::test(CreateLoyaltyCard::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'card_number' => 'LC-0001', 'issue_date' => '2026-07-21', 'expiry_date' => '2027-07-21', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = LoyaltyCard::query()->firstOrFail();
    Livewire::test(ListLoyaltyCards::class)->assertSuccessful()->assertSee('LC-0001');

    Livewire::test(EditLoyaltyCard::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'card_number' => 'LC-0002', 'issue_date' => '2026-07-22', 'expiry_date' => '2027-07-22', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->card_number)->toBe('LC-0002');

    Livewire::test(EditLoyaltyCard::class, ['record' => $record->getKey()])->callAction('delete');
    expect(LoyaltyCard::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
