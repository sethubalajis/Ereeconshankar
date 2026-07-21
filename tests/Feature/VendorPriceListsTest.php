<?php

use App\Filament\Resources\VendorPriceLists\Pages\CreateVendorPriceList;
use App\Filament\Resources\VendorPriceLists\Pages\EditVendorPriceList;
use App\Filament\Resources\VendorPriceLists\Pages\ListVendorPriceLists;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPriceList;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorPriceListsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createVendorPriceListSetup(): array
{
    $vendor = Vendor::query()->create(['name' => 'VPL Vendor', 'code' => 'VPL01', 'status' => true]);
    $product = Product::query()->create(['name' => 'VPL Product', 'code' => 'VPLP1', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'VPL-SKU-1', 'cost_price' => 10, 'selling_price' => 12, 'mrp' => 14, 'is_active' => true]);
    return compact('vendor', 'product', 'variant');
}

it('redirects guests away from vendor price lists', function (): void {
    get('/admin/vendor-price-lists')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor price list page', function (): void {
    get('/admin/vendor-price-lists/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor price list', function (): void {
    actingAs(makeVendorPriceListsAdminUser());

    Livewire::test(CreateVendorPriceList::class)
        ->fillForm(['vendor_id' => null, 'product_id' => null, 'agreed_price' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'product_id' => 'required', 'agreed_price' => 'required']);
});

it('performs vendor price list crud operations', function (): void {
    actingAs(makeVendorPriceListsAdminUser());
    ['vendor' => $vendor, 'product' => $product, 'variant' => $variant] = createVendorPriceListSetup();

    Livewire::test(CreateVendorPriceList::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'agreed_price' => 11,
            'valid_from' => '2026-07-21',
            'valid_to' => '2026-12-31',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorPriceList::query()->firstOrFail();
    Livewire::test(ListVendorPriceLists::class)->assertSuccessful()->assertSee('VPL Vendor');

    Livewire::test(EditVendorPriceList::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'agreed_price' => 12,
            'valid_from' => '2026-08-01',
            'valid_to' => '2027-01-31',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->agreed_price)->toBe(12.0);

    Livewire::test(EditVendorPriceList::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorPriceList::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
