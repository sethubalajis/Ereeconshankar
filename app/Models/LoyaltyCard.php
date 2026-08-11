<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyCard extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->card_number)) {
                $model->card_number = 'LC-' . strtoupper(\Illuminate\Support\Str::random(10));
            }
        });
    }

    //
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
