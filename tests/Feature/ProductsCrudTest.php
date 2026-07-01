<?php

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Brand;
use App\Models\HsnCode;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductUnit;
use App\Models\Role;
use App\Models\TaxGroup;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs product crud operations', function (): void {
    actingAs(makeProductCrudAdminUser());

    $brand = Brand::query()->create(['name' => 'Brand P', 'code' => 'BRDP01', 'status' => true]);
    $category = ProductCategory::query()->create(['name' => 'Category P', 'code' => 'PCATP1', 'status' => true]);
    $subCategory = ProductSubCategory::query()->create([
        'product_category_id' => $category->getKey(),
        'name' => 'Sub P',
        'code' => 'PSCP01',
        'status' => true,
    ]);
    $unit = ProductUnit::query()->create(['name' => 'Piece P', 'code' => 'UNTP01']);
    $hsn = HsnCode::query()->create(['hsn_code' => 'HSNP001']);
    $tax = TaxGroup::query()->create(['name' => 'Tax P', 'total_tax_percentage' => 5]);

    Livewire::test(CreateProduct::class)
        ->fillForm([
            'brand_id' => $brand->getKey(),
            'product_category_id' => $category->getKey(),
            'product_sub_category_id' => $subCategory->getKey(),
            'product_unit_id' => $unit->getKey(),
            'hsn_code_id' => $hsn->getKey(),
            'tax_group_id' => $tax->getKey(),
            'name' => 'Product CRUD',
            'code' => 'PRD001',
            'sku' => 'SKU001',
            'description' => 'Product CRUD test',
            'base_price' => 199.99,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Product::query()->where('code', 'PRD001')->firstOrFail();

    Livewire::test(ListProducts::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditProduct::class, ['record' => $record->getKey()])
        ->fillForm([
            'brand_id' => $brand->getKey(),
            'product_category_id' => $category->getKey(),
            'product_sub_category_id' => $subCategory->getKey(),
            'product_unit_id' => $unit->getKey(),
            'hsn_code_id' => $hsn->getKey(),
            'tax_group_id' => $tax->getKey(),
            'name' => 'Product CRUD Updated',
            'code' => 'PRD002',
            'sku' => 'SKU002',
            'description' => 'Product CRUD updated',
            'base_price' => 249.99,
            'is_active' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('PRD002');

    Livewire::test(EditProduct::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(Product::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
