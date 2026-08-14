<?php

use App\Filament\Resources\PickingLists\Pages\CreatePickingList;
use App\Filament\Resources\PickingLists\Pages\EditPickingList;
use App\Filament\Resources\PickingLists\Pages\ListPickingLists;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PickingList;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePickingListsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPickingListWarehouse(): Warehouse
{
    $company = Company::query()->create(['name' => 'Pick Co', 'code' => 'PIC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Pick Branch', 'code' => 'PIB01', 'status' => true]);
    return Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Pick Warehouse', 'code' => 'PIW01', 'status' => true]);
}

it('redirects guests away from picking lists list', function (): void { get('/admin/picking-lists')->assertRedirect('/admin/login'); });
it('redirects guests away from create picking list page', function (): void { get('/admin/picking-lists/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a picking list', function (): void {
    actingAs(makePickingListsAdminUser());

    Livewire::test(CreatePickingList::class)
        ->fillForm(['warehouse_id' => null, 'reference_name' => '', 'reference_type' => '', 'status' => ''])
        ->call('create')
        ->assertHasFormErrors(['warehouse_id' => 'required', 'reference_name' => 'required', 'status' => 'required']);
});

it('performs picking list crud operations', function (): void {
    actingAs(makePickingListsAdminUser());
    $warehouse = createPickingListWarehouse();

    Livewire::test(CreatePickingList::class)
        ->fillForm(['warehouse_id' => $warehouse->getKey(), 'reference_name' => 'SO-501', 'reference_type' => 'App\Models\SalesOrder', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PickingList::query()->firstOrFail();
    Livewire::test(ListPickingLists::class)->assertSuccessful()->assertSee('pending');

    Livewire::test(EditPickingList::class, ['record' => $record->getKey()])
        ->fillForm(['warehouse_id' => $warehouse->getKey(), 'reference_name' => 'SO-502', 'reference_type' => 'App\Models\WarehouseTransfer', 'status' => 'completed'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('completed');

    Livewire::test(EditPickingList::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PickingList::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
