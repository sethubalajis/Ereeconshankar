<?php

use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Color;
use App\Models\FinancialYear;
use App\Models\MerchandisePlanning;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\ProductVariant;
use App\Models\Season;
use App\Models\Size;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

it('defines merchandise planning relationships', function (): void {
    $planning = new MerchandisePlanning();
    $financialYear = new FinancialYear();
    $season = new Season();

    expect($planning->financialYear())->toBeInstanceOf(BelongsTo::class)
        ->and($planning->season())->toBeInstanceOf(BelongsTo::class)
        ->and($financialYear->merchandisePlannings())->toBeInstanceOf(HasMany::class)
        ->and($season->merchandisePlannings())->toBeInstanceOf(HasMany::class);
});

it('defines product hierarchy relationships', function (): void {
    $brand = new Brand();
    $category = new ProductCategory();
    $subCategory = new ProductSubCategory();
    $product = new Product();

    expect($brand->products())->toBeInstanceOf(HasMany::class)
        ->and($category->productCategory())->toBeInstanceOf(BelongsTo::class)
        ->and($category->childCategories())->toBeInstanceOf(HasMany::class)
        ->and($category->productSubCategories())->toBeInstanceOf(HasMany::class)
        ->and($category->products())->toBeInstanceOf(HasMany::class)
        ->and($subCategory->productCategory())->toBeInstanceOf(BelongsTo::class)
        ->and($subCategory->products())->toBeInstanceOf(HasMany::class)
        ->and($product->brand())->toBeInstanceOf(BelongsTo::class)
        ->and($product->productCategory())->toBeInstanceOf(BelongsTo::class)
        ->and($product->productSubCategory())->toBeInstanceOf(BelongsTo::class)
        ->and($product->productVariants())->toBeInstanceOf(HasMany::class)
        ->and($product->productImages())->toBeInstanceOf(HasMany::class)
        ->and($product->productBarcodes())->toBeInstanceOf(HasMany::class);
});

it('defines variant and attribute relationships', function (): void {
    $color = new Color();
    $size = new Size();
    $variant = new ProductVariant();
    $attribute = new ProductAttribute();
    $attributeValue = new AttributeValue();

    expect($color->productVariants())->toBeInstanceOf(HasMany::class)
        ->and($size->productVariants())->toBeInstanceOf(HasMany::class)
        ->and($variant->product())->toBeInstanceOf(BelongsTo::class)
        ->and($variant->color())->toBeInstanceOf(BelongsTo::class)
        ->and($variant->size())->toBeInstanceOf(BelongsTo::class)
        ->and($variant->attributeValues())->toBeInstanceOf(BelongsToMany::class)
        ->and($variant->productImages())->toBeInstanceOf(HasMany::class)
        ->and($variant->productBarcodes())->toBeInstanceOf(HasMany::class)
        ->and($attribute->attributeValues())->toBeInstanceOf(HasMany::class)
        ->and($attributeValue->productAttribute())->toBeInstanceOf(BelongsTo::class)
        ->and($attributeValue->productVariants())->toBeInstanceOf(BelongsToMany::class);
});