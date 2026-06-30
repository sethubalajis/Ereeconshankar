<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinStock extends Model
{
    protected $guarded = [];

    //
    public function bin() {
        return $this->belongsTo(Bin::class, 'bin_id');
    }
    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
