<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountRule extends Model
{
    protected $guarded = [];

    //
    public function promotionType() {
        return $this->belongsTo(PromotionType::class, 'promotion_type_id');
    }
}
