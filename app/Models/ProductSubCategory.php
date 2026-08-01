<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductSubCategory extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (self $subCategory): void {
            if (empty($subCategory->code)) {
                $lastSubCategory = self::orderBy('id', 'desc')->first();
                $nextId = $lastSubCategory ? $lastSubCategory->id + 1 : 1;
                $subCategory->code = 'SUBCAT-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function productCategory() {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_sub_category_id');
    }
}
