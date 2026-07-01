<?php

use App\Filament\Resources\ProductSubCategories\Pages\CreateProductSubCategory;
use App\Filament\Resources\ProductSubCategories\Pages\EditProductSubCategory;
use App\Filament\Resources\ProductSubCategories\Pages\ListProductSubCategories;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductSubCategoryCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs product sub category crud operations', function (): void {
    actingAs(makeProductSubCategoryCrudAdminUser());

    $category = ProductCategory::query()->create([
        'name' => 'Apparel',
        'code' => 'PCAT10',
        'description' => 'Parent category',
        'status' => true,
    ]);

    Livewire::test(CreateProductSubCategory::class)
        ->fillForm([
            'product_category_id' => $category->getKey(),
            'name' => 'Men Shirts',
            'code' => 'PSC001',
            'description' => 'Sub category CRUD test',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductSubCategory::query()->where('code', 'PSC001')->firstOrFail();

    Livewire::test(ListProductSubCategories::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditProductSubCategory::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_category_id' => $category->getKey(),
            'name' => 'Women Tops',
            'code' => 'PSC002',
            'description' => 'Sub category CRUD updated',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('PSC002');

    Livewire::test(EditProductSubCategory::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductSubCategory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
