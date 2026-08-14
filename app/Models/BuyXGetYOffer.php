<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class BuyXGetYOffer extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function buyProduct() {
        return $this->belongsTo(Product::class, 'buy_product_id');
    }
    public function getProduct() {
        return $this->belongsTo(Product::class, 'get_product_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
