<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyXGetYOffer extends Model
{
    protected $guarded = [];

    //
    public function buyProduct() {
        return $this->belongsTo(Product::class, 'buy_product_id');
    }
    public function getProduct() {
        return $this->belongsTo(Product::class, 'get_product_id');
    }
}
