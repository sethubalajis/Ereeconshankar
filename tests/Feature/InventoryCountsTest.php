<?php

use App\Filament\Resources\InventoryCounts\Pages\CreateInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\EditInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\ListInventoryCounts;
use App\Models\Branch;
use App\Models\Company;
use App\Models\InventoryCount;
use App\Models\InventoryReconciliation;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Stock;
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

it('does not create reconciliation rows while status is draft', function (): void {
    ['branch' => $branch, 'warehouse' => $warehouse] = createInventoryCountSetup();

    $category = ProductCategory::query()->create(['name' => 'Draft Cat', 'code' => 'DCAT01', 'status' => true]);
    $product = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Draft Product', 'code' => 'DPR001', 'is_active' => true]);

    Stock::query()->create([
        'product_id' => $product->getKey(),
        'product_variant_id' => null,
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'quantity' => 25,
        'avg_cost' => 10,
    ]);

    $count = InventoryCount::query()->create([
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'count_date' => '2026-07-23',
        'status' => 'draft',
    ]);

    expect(InventoryReconciliation::query()->where('inventory_count_id', $count->getKey())->count())->toBe(0);
});

it('creates reconciliation rows when status changes from draft to active', function (): void {
    ['branch' => $branch, 'warehouse' => $warehouse] = createInventoryCountSetup();

    $category = ProductCategory::query()->create(['name' => 'Active Cat', 'code' => 'ACAT01', 'status' => true]);
    $productA = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Active Product A', 'code' => 'APR001', 'is_active' => true]);
    $productB = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Active Product B', 'code' => 'APR002', 'is_active' => true]);

    Stock::query()->create([
        'product_id' => $productA->getKey(),
        'product_variant_id' => null,
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'quantity' => 12,
        'avg_cost' => 10,
    ]);

    Stock::query()->create([
        'product_id' => $productB->getKey(),
        'product_variant_id' => null,
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'quantity' => 8,
        'avg_cost' => 15,
    ]);

    $count = InventoryCount::query()->create([
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'count_date' => '2026-07-23',
        'status' => 'draft',
    ]);

    expect(InventoryReconciliation::query()->where('inventory_count_id', $count->getKey())->count())->toBe(0);

    $count->status = 'active';
    $count->save();

    $rows = InventoryReconciliation::query()->where('inventory_count_id', $count->getKey())->get();

    expect($rows->count())->toBe(2);
    expect((float) $rows->firstWhere('product_id', $productA->getKey())->system_qty)->toBe(12.0);
    expect((float) $rows->firstWhere('product_id', $productA->getKey())->physical_qty)->toBe(0.0);
    expect((float) $rows->firstWhere('product_id', $productA->getKey())->variance)->toBe(-12.0);

    $count->save();

    expect(InventoryReconciliation::query()->where('inventory_count_id', $count->getKey())->count())->toBe(2);
});

it('matches documented inventory count to reconciliation example flow', function (): void {
    ['branch' => $branch, 'warehouse' => $warehouse] = createInventoryCountSetup();

    $category = ProductCategory::query()->create(['name' => 'Doc Cat', 'code' => 'DOC001', 'status' => true]);
    $productOne = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Doc Product One', 'code' => 'DPR101', 'is_active' => true]);
    $productTwo = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Doc Product Two', 'code' => 'DPR102', 'is_active' => true]);

    Stock::query()->create([
        'product_id' => $productOne->getKey(),
        'product_variant_id' => null,
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'quantity' => 120,
        'avg_cost' => 20,
    ]);

    Stock::query()->create([
        'product_id' => $productTwo->getKey(),
        'product_variant_id' => null,
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'quantity' => 35,
        'avg_cost' => 15,
    ]);

    $count = InventoryCount::query()->create([
        'branch_id' => $branch->getKey(),
        'warehouse_id' => $warehouse->getKey(),
        'count_date' => '2026-07-24',
        'status' => 'draft',
    ]);

    expect(InventoryReconciliation::query()->where('inventory_count_id', $count->getKey())->count())->toBe(0);

    $count->status = 'active';
    $count->save();

    $lineOne = InventoryReconciliation::query()
        ->where('inventory_count_id', $count->getKey())
        ->where('product_id', $productOne->getKey())
        ->firstOrFail();

    $lineTwo = InventoryReconciliation::query()
        ->where('inventory_count_id', $count->getKey())
        ->where('product_id', $productTwo->getKey())
        ->firstOrFail();

    expect((float) $lineOne->system_qty)->toBe(120.0);
    expect((float) $lineOne->physical_qty)->toBe(0.0);
    expect((float) $lineOne->variance)->toBe(-120.0);

    expect((float) $lineTwo->system_qty)->toBe(35.0);
    expect((float) $lineTwo->physical_qty)->toBe(0.0);
    expect((float) $lineTwo->variance)->toBe(-35.0);

    $lineOne->physical_qty = 118;
    $lineOne->save();

    $lineTwo->physical_qty = 40;
    $lineTwo->save();

    $lineOne->refresh();
    $lineTwo->refresh();

    expect((float) $lineOne->variance)->toBe(-2.0);
    expect((float) $lineTwo->variance)->toBe(5.0);
});
