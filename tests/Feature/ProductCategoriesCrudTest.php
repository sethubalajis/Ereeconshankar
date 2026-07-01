<?php

use App\Filament\Resources\ProductCategories\Pages\CreateProductCategory;
use App\Filament\Resources\ProductCategories\Pages\EditProductCategory;
use App\Filament\Resources\ProductCategories\Pages\ListProductCategories;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductCategoryCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('creates a product category record', function (): void {
    actingAs(makeProductCategoryCrudAdminUser());

    Livewire::test(CreateProductCategory::class)
        ->fillForm([
            'parent_id' => null,
            'name' => 'PC CRUD Create',
            'code' => 'PCC001',
            'description' => 'Created from CRUD test',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(ProductCategory::query()->where('code', 'PCC001')->exists())->toBeTrue();
});

it('reads product categories from list page', function (): void {
    actingAs(makeProductCategoryCrudAdminUser());

    $category = ProductCategory::query()->create([
        'parent_id' => null,
        'name' => 'PC CRUD Read',
        'code' => 'PCC002',
        'description' => 'Read test row',
        'status' => true,
    ]);

    Livewire::test(ListProductCategories::class)
        ->assertSuccessful()
        ->assertSee($category->name)
        ->assertSee($category->code);
});

it('updates a product category record', function (): void {
    actingAs(makeProductCategoryCrudAdminUser());

    $category = ProductCategory::query()->create([
        'parent_id' => null,
        'name' => 'PC CRUD Update Old',
        'code' => 'PCC003',
        'description' => 'Old description',
        'status' => true,
    ]);

    Livewire::test(EditProductCategory::class, ['record' => $category->getKey()])
        ->fillForm([
            'parent_id' => null,
            'name' => 'PC CRUD Update New',
            'code' => 'PCC003U',
            'description' => 'Updated description',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $category->refresh();

    expect($category->name)->toBe('PC CRUD Update New');
    expect($category->code)->toBe('PCC003U');
});

it('deletes a product category record', function (): void {
    actingAs(makeProductCategoryCrudAdminUser());

    $category = ProductCategory::query()->create([
        'parent_id' => null,
        'name' => 'PC CRUD Delete',
        'code' => 'PCC004',
        'description' => 'Delete test row',
        'status' => true,
    ]);

    Livewire::test(EditProductCategory::class, ['record' => $category->getKey()])
        ->callAction('delete');

    expect(ProductCategory::query()->whereKey($category->getKey())->exists())->toBeFalse();
});
