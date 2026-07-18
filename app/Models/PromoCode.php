<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = 'PC-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    //
}
