<?php

use App\Filament\Resources\PackingLists\Pages\CreatePackingList;
use App\Filament\Resources\PackingLists\Pages\EditPackingList;
use App\Filament\Resources\PackingLists\Pages\ListPackingLists;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PackingList;
use App\Models\PickingList;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePackingListsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPackingListSetup(): PickingList
{
    $company = Company::query()->create(['name' => 'Pack Co', 'code' => 'PKCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Pack Branch', 'code' => 'PKBR1', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Pack WH', 'code' => 'PKWH1', 'status' => true]);

    return PickingList::query()->create([
        'warehouse_id' => $warehouse->getKey(),
        'reference_id' => 101,
        'reference_type' => 'stock_transfer',
        'status' => 'pending',
    ]);
}

it('redirects guests away from packing lists list', function (): void { get('/admin/packing-lists')->assertRedirect('/admin/login'); });
it('redirects guests away from create packing list page', function (): void { get('/admin/packing-lists/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a packing list', function (): void {
    actingAs(makePackingListsAdminUser());

    Livewire::test(CreatePackingList::class)
        ->fillForm(['picking_list_id' => null, 'status' => ''])
        ->call('create')
        ->assertHasFormErrors(['picking_list_id' => 'required', 'status' => 'required']);
});

it('performs packing list crud operations', function (): void {
    actingAs(makePackingListsAdminUser());
    $pickingList = createPackingListSetup();

    Livewire::test(CreatePackingList::class)
        ->fillForm(['picking_list_id' => $pickingList->getKey(), 'status' => 'packed'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PackingList::query()->firstOrFail();
    Livewire::test(ListPackingLists::class)->assertSuccessful()->assertSee('packed');

    Livewire::test(EditPackingList::class, ['record' => $record->getKey()])
        ->fillForm(['picking_list_id' => $pickingList->getKey(), 'status' => 'active'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('active');

    Livewire::test(EditPackingList::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PackingList::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
