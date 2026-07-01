<?php

use App\Filament\Resources\ProductBarcodes\Pages\CreateProductBarcode;
use App\Filament\Resources\ProductBarcodes\Pages\EditProductBarcode;
use App\Filament\Resources\ProductBarcodes\Pages\ListProductBarcodes;
use App\Models\Product;
use App\Models\ProductBarcode;
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

it('performs product barcode crud operations', function (): void {
    actingAs(makeProductBarcodeCrudAdminUser());

    $product = Product::query()->create([
        'name' => 'Barcode Parent',
        'code' => 'PRDB01',
        'base_price' => 100,
        'is_active' => true,
    ]);

    Livewire::test(CreateProductBarcode::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => null,
            'barcode' => 'PBC001',
            'barcode_type' => 'Code128',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductBarcode::query()->where('barcode', 'PBC001')->firstOrFail();

    Livewire::test(ListProductBarcodes::class)
        ->assertSuccessful()
        ->assertSee($record->barcode);

    Livewire::test(EditProductBarcode::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => null,
            'barcode' => 'PBC002',
            'barcode_type' => 'EAN13',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->barcode)->toBe('PBC002');

    Livewire::test(EditProductBarcode::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductBarcode::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
