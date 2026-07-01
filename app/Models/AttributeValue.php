<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $guarded = [];

    //
    public function productAttribute() {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function productVariants()
    {
        return $this->belongsToMany(ProductVariant::class, 'attribute_value_product_variant');
    }
}
