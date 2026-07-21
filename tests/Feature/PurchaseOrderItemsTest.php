<?php

use App\Filament\Resources\PurchaseOrderItems\Pages\CreatePurchaseOrderItem;
use App\Filament\Resources\PurchaseOrderItems\Pages\EditPurchaseOrderItem;
use App\Filament\Resources\PurchaseOrderItems\Pages\ListPurchaseOrderItems;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePurchaseOrderItemsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createPurchaseOrderItemsSetup(): array
{
    $company = Company::query()->create(['name' => 'POI Company', 'code' => 'POIC1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'POI Branch', 'code' => 'POIB1', 'status' => true]);
    $vendor = Vendor::query()->create(['name' => 'POI Vendor', 'code' => 'POIV1', 'status' => true]);
    $purchaseOrder = PurchaseOrder::query()->create([
        'vendor_id' => $vendor->getKey(),
        'branch_id' => $branch->getKey(),
        'po_date' => '2026-07-21',
        'expected_delivery_date' => '2026-07-31',
        'total_amount' => 100,
        'status' => 'draft',
    ]);
    $product = Product::query()->create(['name' => 'POI Product', 'code' => 'POIP1', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'POI-SKU-1', 'cost_price' => 100, 'selling_price' => 120, 'mrp' => 130, 'is_active' => true]);

    return compact('purchaseOrder', 'product', 'variant');
}

it('redirects guests away from purchase order items list', function (): void {
    get('/admin/purchase-order-items')->assertRedirect('/admin/login');
});

it('redirects guests away from create purchase order item page', function (): void {
    get('/admin/purchase-order-items/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a purchase order item', function (): void {
    actingAs(makePurchaseOrderItemsAdminUser());

    Livewire::test(CreatePurchaseOrderItem::class)
        ->fillForm([
            'purchase_order_id' => null,
            'product_id' => null,
            'quantity' => null,
            'unit_price' => null,
            'total_price' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'purchase_order_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'unit_price' => 'required',
            'total_price' => 'required',
        ]);
});

it('performs purchase order item crud operations', function (): void {
    actingAs(makePurchaseOrderItemsAdminUser());
    ['purchaseOrder' => $purchaseOrder, 'product' => $product, 'variant' => $variant] = createPurchaseOrderItemsSetup();

    Livewire::test(CreatePurchaseOrderItem::class)
        ->fillForm([
            'purchase_order_id' => $purchaseOrder->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 2,
            'unit_price' => 100,
            'total_price' => 200,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PurchaseOrderItem::query()->firstOrFail();

    Livewire::test(ListPurchaseOrderItems::class)
        ->assertSuccessful()
        ->assertSee('POI Product');

    Livewire::test(EditPurchaseOrderItem::class, ['record' => $record->getKey()])
        ->fillForm([
            'purchase_order_id' => $purchaseOrder->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 3,
            'unit_price' => 100,
            'total_price' => 300,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((int) $record->quantity)->toBe(3);

    Livewire::test(EditPurchaseOrderItem::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PurchaseOrderItem::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
