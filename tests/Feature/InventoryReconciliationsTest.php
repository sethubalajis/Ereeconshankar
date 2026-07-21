<?php

use App\Filament\Resources\InventoryReconciliations\Pages\CreateInventoryReconciliation;
use App\Filament\Resources\InventoryReconciliations\Pages\EditInventoryReconciliation;
use App\Filament\Resources\InventoryReconciliations\Pages\ListInventoryReconciliations;
use App\Models\Branch;
use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\InventoryReconciliation;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeInventoryReconciliationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createInventoryReconciliationSetup(): array
{
    $company = Company::query()->create(['name' => 'Recon Co', 'code' => 'RCO001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Recon Branch', 'code' => 'RBR001', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Recon WH', 'code' => 'RWH001', 'status' => true]);
    $inventoryCount = InventoryCount::query()->create(['branch_id' => $branch->getKey(), 'warehouse_id' => $warehouse->getKey(), 'count_date' => '2026-07-21', 'status' => 'completed']);
    $category = ProductCategory::query()->create(['name' => 'Recon Cat', 'code' => 'RCAT01', 'status' => true]);
    $product = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Recon Product', 'code' => 'RPR001', 'is_active' => true]);
    return compact('inventoryCount', 'product');
}

it('redirects guests away from inventory reconciliations list', function (): void { get('/admin/inventory-reconciliations')->assertRedirect('/admin/login'); });
it('redirects guests away from create inventory reconciliation page', function (): void { get('/admin/inventory-reconciliations/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an inventory reconciliation', function (): void {
    actingAs(makeInventoryReconciliationsAdminUser());
    Livewire::test(CreateInventoryReconciliation::class)
        ->fillForm(['inventory_count_id' => null, 'product_id' => null, 'product_variant_id' => null, 'system_qty' => null, 'physical_qty' => null, 'variance' => null])
        ->call('create')
        ->assertHasFormErrors(['inventory_count_id' => 'required', 'product_id' => 'required', 'system_qty' => 'required', 'physical_qty' => 'required', 'variance' => 'required']);
});

it('performs inventory reconciliation crud operations', function (): void {
    actingAs(makeInventoryReconciliationsAdminUser());
    ['inventoryCount' => $inventoryCount, 'product' => $product] = createInventoryReconciliationSetup();

    Livewire::test(CreateInventoryReconciliation::class)
        ->fillForm(['inventory_count_id' => $inventoryCount->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => null, 'system_qty' => 100, 'physical_qty' => 95, 'variance' => -5])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = InventoryReconciliation::query()->firstOrFail();
    Livewire::test(ListInventoryReconciliations::class)->assertSuccessful()->assertSee('95');

    Livewire::test(EditInventoryReconciliation::class, ['record' => $record->getKey()])
        ->fillForm(['inventory_count_id' => $inventoryCount->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => null, 'system_qty' => 100, 'physical_qty' => 100, 'variance' => 0])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditInventoryReconciliation::class, ['record' => $record->getKey()])->callAction('delete');
});
