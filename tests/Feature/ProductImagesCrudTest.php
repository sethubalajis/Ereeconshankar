<?php

use App\Filament\Resources\ProductImages\Pages\CreateProductImage;
use App\Filament\Resources\ProductImages\Pages\EditProductImage;
use App\Filament\Resources\ProductImages\Pages\ListProductImages;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductImageCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs product image crud operations', function (): void {
    Storage::fake('public');
    actingAs(makeProductImageCrudAdminUser());

    $product = Product::query()->create([
        'name' => 'Image Parent',
        'code' => 'PRDI01',
        'base_price' => 110,
        'is_active' => true,
    ]);

    $createImage = UploadedFile::fake()->image('create-image.png');

    Livewire::test(CreateProductImage::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => null,
            'image_path' => $createImage,
            'is_primary' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductImage::query()->where('product_id', $product->getKey())->firstOrFail();

    Livewire::test(ListProductImages::class)->assertSuccessful();

    $updateImage = UploadedFile::fake()->image('update-image.png');

    Livewire::test(EditProductImage::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => null,
            'image_path' => $updateImage,
            'is_primary' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((bool) $record->is_primary)->toBeFalse();

    Livewire::test(EditProductImage::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(ProductImage::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
