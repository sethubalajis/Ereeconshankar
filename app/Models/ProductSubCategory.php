<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    protected $guarded = [];

    //
    public function productCategory() {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
