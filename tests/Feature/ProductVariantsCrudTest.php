<?php

use App\Filament\Resources\ProductVariants\Pages\CreateProductVariant;
use App\Filament\Resources\ProductVariants\Pages\EditProductVariant;
use App\Filament\Resources\ProductVariants\Pages\ListProductVariants;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
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

it('performs product variant crud operations', function (): void {
    actingAs(makeProductVariantCrudAdminUser());

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
