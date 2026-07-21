<?php

use App\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePurchaseOrdersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createPurchaseOrderSetup(): array
{
    $company = Company::query()->create(['name' => 'PO Company', 'code' => 'POC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'PO Branch', 'code' => 'POB01', 'status' => true]);
    $vendor = Vendor::query()->create(['name' => 'PO Vendor', 'code' => 'POV01', 'status' => true]);
    $product = Product::query()->create(['name' => 'PO Product', 'code' => 'POPR1', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'PO-SKU-1', 'cost_price' => 100, 'selling_price' => 120, 'mrp' => 130, 'is_active' => true]);

    return compact('branch', 'vendor', 'product', 'variant');
}

it('redirects guests away from purchase orders list', function (): void {
    get('/admin/purchase-orders')->assertRedirect('/admin/login');
});

it('redirects guests away from create purchase order page', function (): void {
    get('/admin/purchase-orders/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a purchase order', function (): void {
    actingAs(makePurchaseOrdersAdminUser());

    Livewire::test(CreatePurchaseOrder::class)
        ->fillForm([
            'vendor_id' => null,
            'branch_id' => null,
            'po_date' => null,
            'total_amount' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'vendor_id' => 'required',
            'branch_id' => 'required',
            'po_date' => 'required',
            'total_amount' => 'required',
            'status' => 'required',
        ]);
});

it('performs purchase order crud operations', function (): void {
    actingAs(makePurchaseOrdersAdminUser());
    ['branch' => $branch, 'vendor' => $vendor, 'product' => $product, 'variant' => $variant] = createPurchaseOrderSetup();

    Livewire::test(CreatePurchaseOrder::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'branch_id' => $branch->getKey(),
            'po_date' => '2026-07-21',
            'expected_delivery_date' => '2026-07-31',
            'total_amount' => 100,
            'status' => 'submitted',
            'items' => [
                [
                    'product_id' => $product->getKey(),
                    'product_variant_id' => $variant->getKey(),
                    'quantity' => 1,
                    'unit_price' => 100,
                    'total_price' => 100,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PurchaseOrder::query()->firstOrFail();

    Livewire::test(ListPurchaseOrders::class)
        ->assertSuccessful()
        ->assertSee($record->po_number);

    Livewire::test(EditPurchaseOrder::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'branch_id' => $branch->getKey(),
            'po_date' => '2026-07-22',
            'expected_delivery_date' => '2026-08-02',
            'total_amount' => 200,
            'status' => 'approved',
            'items' => [
                [
                    'product_id' => $product->getKey(),
                    'product_variant_id' => $variant->getKey(),
                    'quantity' => 2,
                    'unit_price' => 100,
                    'total_price' => 200,
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditPurchaseOrder::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PurchaseOrder::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
