<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    //
    public function brand() {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function productCategory() {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    public function productSubCategory() {
        return $this->belongsTo(ProductSubCategory::class, 'product_sub_category_id');
    }
    public function productUnit() {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }
    public function hsnCode() {
        return $this->belongsTo(HsnCode::class, 'hsn_code_id');
    }
    public function taxGroup() {
        return $this->belongsTo(TaxGroup::class, 'tax_group_id');
    }
}
