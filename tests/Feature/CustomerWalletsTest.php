<?php

use App\Filament\Resources\CustomerWallets\Pages\CreateCustomerWallet;
use App\Filament\Resources\CustomerWallets\Pages\EditCustomerWallet;
use App\Filament\Resources\CustomerWallets\Pages\ListCustomerWallets;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CustomerWallet;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCustomerWalletsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCustomerWalletCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Wallet Group', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create(['customer_group_id' => $group->getKey(), 'name' => 'Wallet Customer', 'phone' => '9000000004', 'total_points' => 0, 'wallet_balance' => 0, 'status' => true]);
}

it('redirects guests away from customer wallets list', function (): void { get('/admin/customer-wallets')->assertRedirect('/admin/login'); });
it('redirects guests away from create customer wallet page', function (): void { get('/admin/customer-wallets/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a customer wallet', function (): void {
    actingAs(makeCustomerWalletsAdminUser());
    Livewire::test(CreateCustomerWallet::class)
        ->fillForm(['customer_id' => null, 'transaction_type' => null, 'amount' => null, 'description' => '', 'transaction_date' => null])
        ->call('create')
        ->assertHasFormErrors(['customer_id' => 'required', 'transaction_type' => 'required', 'amount' => 'required', 'transaction_date' => 'required']);
});

it('performs customer wallet crud operations', function (): void {
    actingAs(makeCustomerWalletsAdminUser());
    $customer = createCustomerWalletCustomer();

    Livewire::test(CreateCustomerWallet::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'transaction_type' => 'credit', 'amount' => 500, 'description' => 'Wallet top up', 'transaction_date' => '2026-07-21'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CustomerWallet::query()->firstOrFail();
    Livewire::test(ListCustomerWallets::class)->assertSuccessful()->assertSee('Wallet top up');

    Livewire::test(EditCustomerWallet::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'transaction_type' => 'debit', 'amount' => 250, 'description' => 'Purchase adjustment', 'transaction_date' => '2026-07-22'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->transaction_type)->toBe('debit');

    Livewire::test(EditCustomerWallet::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CustomerWallet::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
