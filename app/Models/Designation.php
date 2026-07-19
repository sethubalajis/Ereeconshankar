<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = static::where('code', 'like', 'DSG-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('DSG-', '', $latest->code)) + 1 : 1;
                $model->code = 'DSG-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
}
