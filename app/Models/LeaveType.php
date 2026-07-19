<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = static::where('code', 'like', 'LT-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('LT-', '', $latest->code)) + 1 : 1;
                $model->code = 'LT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
}
