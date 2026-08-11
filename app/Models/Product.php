<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            if (empty($product->code)) {
                $lastProduct = self::orderBy('id', 'desc')->first();
                $nextId = $lastProduct ? $lastProduct->id + 1 : 1;
                $product->code = 'PRD-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productSubCategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubCategory::class, 'product_sub_category_id');
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function hsnCode(): BelongsTo
    {
        return $this->belongsTo(HsnCode::class, 'hsn_code_id');
    }

    public function taxGroup(): BelongsTo
    {
        return $this->belongsTo(TaxGroup::class, 'tax_group_id');
    }

    public function productVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function productBarcodes(): HasMany
    {
        return $this->hasMany(ProductBarcode::class, 'product_id');
    }

    public function priceListItems(): HasMany
    {
        return $this->hasMany(PriceListItem::class, 'product_id');
    }
}
