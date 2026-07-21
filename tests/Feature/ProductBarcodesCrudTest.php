<?php

use App\Filament\Resources\ProductBarcodes\ProductBarcodeResource;
use App\Filament\Resources\ProductVariants\Schemas\ProductVariantForm;
use App\Filament\Resources\ProductBarcodes\Pages\ListProductBarcodes;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductBarcodeCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('shows product barcodes synced from product variants as view only', function (): void {
    actingAs(makeProductBarcodeCrudAdminUser());

    $product = Product::query()->create([
        'name' => 'Variant Barcode Parent',
        'code' => 'PRDB01',
        'is_active' => true,
    ]);

    $variant = ProductVariant::query()->create([
        'product_id' => $product->getKey(),
        'sku' => 'VARPBC01',
        'mrp' => 150,
        'selling_price' => 130,
        'cost_price' => 100,
        'barcode' => 'PBC001',
        'is_active' => true,
    ]);

    ProductVariantForm::syncBarcodeRecord($variant);

    $record = ProductBarcode::query()->where('barcode', 'PBC001')->firstOrFail();

    Livewire::test(ListProductBarcodes::class)
        ->assertSuccessful()
        ->assertSee($record->barcode);

    $pages = ProductBarcodeResource::getPages();

    expect(array_key_exists('create', $pages))->toBeFalse();
    expect(array_key_exists('edit', $pages))->toBeFalse();
});
