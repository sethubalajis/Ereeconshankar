<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $category): void {
            if (empty($category->code)) {
                $lastCategory = self::orderBy('id', 'desc')->first();
                $nextId = $lastCategory ? $lastCategory->id + 1 : 1;
                $category->code = 'CAT-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function productCategory() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function childCategories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function productSubCategories(): HasMany
    {
        return $this->hasMany(ProductSubCategory::class, 'product_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
