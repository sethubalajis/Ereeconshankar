<?php

use App\Filament\Resources\InventoryCounts\Pages\CreateInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\EditInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\ListInventoryCounts;
use App\Models\Branch;
use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeInventoryCountsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createInventoryCountSetup(): array
{
    $company = Company::query()->create(['name' => 'Count Co', 'code' => 'CCO001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Count Branch', 'code' => 'CBR001', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Count WH', 'code' => 'CWH001', 'status' => true]);
    return compact('branch', 'warehouse');
}

it('redirects guests away from inventory counts list', function (): void { get('/admin/inventory-counts')->assertRedirect('/admin/login'); });
it('redirects guests away from create inventory count page', function (): void { get('/admin/inventory-counts/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an inventory count', function (): void {
    actingAs(makeInventoryCountsAdminUser());
    Livewire::test(CreateInventoryCount::class)
        ->fillForm(['branch_id' => null, 'warehouse_id' => null, 'count_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'count_date' => 'required', 'status' => 'required']);
});

it('performs inventory count crud operations', function (): void {
    actingAs(makeInventoryCountsAdminUser());
    ['branch' => $branch, 'warehouse' => $warehouse] = createInventoryCountSetup();

    Livewire::test(CreateInventoryCount::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'warehouse_id' => $warehouse->getKey(), 'count_date' => '2026-07-21', 'status' => 'draft'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = InventoryCount::query()->firstOrFail();
    Livewire::test(ListInventoryCounts::class)->assertSuccessful()->assertSee('draft');

    Livewire::test(EditInventoryCount::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'warehouse_id' => $warehouse->getKey(), 'count_date' => '2026-07-22', 'status' => 'completed'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditInventoryCount::class, ['record' => $record->getKey()])->callAction('delete');
});
