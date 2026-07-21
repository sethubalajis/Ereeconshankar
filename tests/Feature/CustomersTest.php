<?php

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCustomersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCustomersGroup(): CustomerGroup
{
    return CustomerGroup::query()->create(['name' => 'Retail', 'discount_percentage' => 5, 'status' => true]);
}

it('redirects guests away from customers list', function (): void { get('/admin/customers')->assertRedirect('/admin/login'); });
it('redirects guests away from create customer page', function (): void { get('/admin/customers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a customer', function (): void {
    actingAs(makeCustomersAdminUser());
    Livewire::test(CreateCustomer::class)
        ->fillForm(['customer_group_id' => null, 'name' => '', 'phone' => '', 'email' => '', 'address' => '', 'dob' => null, 'anniversary' => null, 'gst_number' => '', 'total_points' => null, 'wallet_balance' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'phone' => 'required', 'total_points' => 'required', 'wallet_balance' => 'required', 'status' => 'required']);
});

it('performs customer crud operations', function (): void {
    actingAs(makeCustomersAdminUser());
    $group = createCustomersGroup();

    Livewire::test(CreateCustomer::class)
        ->fillForm(['customer_group_id' => $group->getKey(), 'name' => 'Acme Customer', 'phone' => '9000000010', 'email' => 'acme@example.com', 'address' => 'Main Street', 'dob' => '1990-01-01', 'anniversary' => '2015-01-01', 'gst_number' => 'GST123', 'total_points' => 10, 'wallet_balance' => 100, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Customer::query()->firstOrFail();
    Livewire::test(ListCustomers::class)->assertSuccessful()->assertSee('Acme Customer');

    Livewire::test(EditCustomer::class, ['record' => $record->getKey()])
        ->fillForm(['customer_group_id' => $group->getKey(), 'name' => 'Acme Updated', 'phone' => '9000000011', 'email' => 'updated@example.com', 'address' => 'Second Street', 'dob' => '1991-02-02', 'anniversary' => '2016-02-02', 'gst_number' => 'GST456', 'total_points' => 20, 'wallet_balance' => 150, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Acme Updated');

    Livewire::test(EditCustomer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Customer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
