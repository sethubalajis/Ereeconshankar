<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $guarded = [];

    public function productCategory(): BelongsTo
    {
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
