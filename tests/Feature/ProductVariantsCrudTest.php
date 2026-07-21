<?php

use App\Filament\Resources\ProductVariants\Pages\CreateProductVariant;
use App\Filament\Resources\ProductVariants\Pages\EditProductVariant;
use App\Filament\Resources\ProductVariants\Pages\ListProductVariants;
use App\Filament\Resources\ProductBarcodes\Pages\ListProductBarcodes;
use App\Models\Product;
use App\Models\ProductBarcode;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductVariantCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function ensureProductsBasePriceColumn(): void
{
    if (! Schema::hasColumn('products', 'base_price')) {
        Schema::table('products', function (Blueprint $table): void {
            $table->decimal('base_price', 15, 2)->default(0);
        });
    }
}

it('performs product variant crud operations', function (): void {
    actingAs(makeProductVariantCrudAdminUser());
    ensureProductsBasePriceColumn();

    $product = Product::query()->create([
        'name' => 'Variant Parent',
        'code' => 'PRDV01',
        'base_price' => 150,
        'is_active' => true,
    ]);

    Livewire::test(CreateProductVariant::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'sku' => 'VAR001',
            'mrp' => 200,
            'selling_price' => 180,
            'cost_price' => 120,
            'barcode' => 'BAR001',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductVariant::query()->where('sku', 'VAR001')->firstOrFail();

    Livewire::test(ListProductVariants::class)
        ->assertSuccessful()
        ->assertSee($record->sku);

    Livewire::test(EditProductVariant::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'sku' => 'VAR002',
            'mrp' => 210,
            'selling_price' => 190,
            'cost_price' => 130,
            'barcode' => 'BAR002',
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->sku)->toBe('VAR002');

    Livewire::test(EditProductVariant::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductVariant::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('creates and updates linked product barcode from product variant', function (): void {
    actingAs(makeProductVariantCrudAdminUser());
    ensureProductsBasePriceColumn();

    $product = Product::query()->create([
        'name' => 'Variant Barcode Parent',
        'code' => 'PRDV02',
        'base_price' => 220,
        'is_active' => true,
    ]);

    Livewire::test(CreateProductVariant::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'sku' => 'VARB001',
            'mrp' => 220,
            'selling_price' => 200,
            'cost_price' => 170,
            'barcode' => 'BARV001',
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $variant = ProductVariant::query()->where('sku', 'VARB001')->firstOrFail();

    $barcode = ProductBarcode::query()
        ->where('product_variant_id', $variant->getKey())
        ->first();

    expect($barcode)->not->toBeNull();
    expect($barcode->barcode)->toBe('BARV001');
    expect($barcode->product_id)->toBe($product->getKey());

    Livewire::test(EditProductVariant::class, ['record' => $variant->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'sku' => 'VARB001',
            'mrp' => 220,
            'selling_price' => 200,
            'cost_price' => 170,
            'barcode' => 'BARV002',
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(
        ProductBarcode::query()->where('product_variant_id', $variant->getKey())->count()
    )->toBe(1);

    $barcode->refresh();
    expect($barcode->barcode)->toBe('BARV002');

    Livewire::test(ListProductBarcodes::class)
        ->assertSuccessful()
        ->assertSee('BARV002');
});
