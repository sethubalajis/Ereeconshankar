<?php

use App\Filament\Resources\CustomerGroups\Pages\CreateCustomerGroup;
use App\Filament\Resources\CustomerGroups\Pages\EditCustomerGroup;
use App\Filament\Resources\CustomerGroups\Pages\ListCustomerGroups;
use App\Models\CustomerGroup;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCustomerGroupsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from customer groups list', function (): void { get('/admin/customer-groups')->assertRedirect('/admin/login'); });
it('redirects guests away from create customer group page', function (): void { get('/admin/customer-groups/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a customer group', function (): void {
    actingAs(makeCustomerGroupsAdminUser());
    Livewire::test(CreateCustomerGroup::class)
        ->fillForm(['name' => '', 'code' => '', 'discount_percentage' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'discount_percentage' => 'required', 'status' => 'required']);
});

it('performs customer group crud operations', function (): void {
    actingAs(makeCustomerGroupsAdminUser());

    Livewire::test(CreateCustomerGroup::class)
        ->fillForm(['name' => 'VIP', 'discount_percentage' => 10, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CustomerGroup::query()->firstOrFail();
    Livewire::test(ListCustomerGroups::class)->assertSuccessful()->assertSee('VIP');

    Livewire::test(EditCustomerGroup::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Elite', 'discount_percentage' => 15, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Elite');

    Livewire::test(EditCustomerGroup::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CustomerGroup::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
