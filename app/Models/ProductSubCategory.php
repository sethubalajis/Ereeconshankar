<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
