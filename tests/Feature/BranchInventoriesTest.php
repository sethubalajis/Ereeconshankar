<?php

use App\Filament\Resources\BranchInventories\Pages\CreateBranchInventory;
use App\Filament\Resources\BranchInventories\Pages\EditBranchInventory;
use App\Filament\Resources\BranchInventories\Pages\ListBranchInventories;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchInventoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBranchInventorySetup(): array
{
    $company = Company::query()->create(['name' => 'Inventory Co', 'code' => 'INVCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Inventory Branch', 'code' => 'INBR001', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Main Warehouse', 'code' => 'WH001', 'status' => true]);
    $category = ProductCategory::query()->create(['name' => 'Inventory Category', 'code' => 'INVPC1', 'status' => true]);
    $product = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Keyboard', 'code' => 'INV001', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'KBD-001', 'mrp' => 100, 'selling_price' => 90, 'cost_price' => 70]);
    return compact('branch', 'warehouse', 'product', 'variant');
}

it('redirects guests away from branch inventories list', function (): void { get('/admin/branch-inventories')->assertRedirect('/admin/login'); });
it('redirects guests away from create branch inventory page', function (): void { get('/admin/branch-inventories/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a branch inventory', function (): void {
    actingAs(makeBranchInventoriesAdminUser());
    Livewire::test(CreateBranchInventory::class)
        ->fillForm(['branch_id' => null, 'warehouse_id' => null, 'product_id' => null, 'product_variant_id' => null, 'quantity' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'warehouse_id' => 'required', 'product_id' => 'required', 'product_variant_id' => 'required', 'quantity' => 'required']);
});

it('performs branch inventory crud operations', function (): void {
    actingAs(makeBranchInventoriesAdminUser());
    ['branch' => $branch, 'warehouse' => $warehouse, 'product' => $product, 'variant' => $variant] = createBranchInventorySetup();

    Livewire::test(CreateBranchInventory::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'warehouse_id' => $warehouse->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => $variant->getKey(), 'quantity' => 20])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchInventory::query()->firstOrFail();
    Livewire::test(ListBranchInventories::class)->assertSuccessful()->assertSee('20');

    Livewire::test(EditBranchInventory::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'warehouse_id' => $warehouse->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => $variant->getKey(), 'quantity' => 25])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(25.0);

    Livewire::test(EditBranchInventory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchInventory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
