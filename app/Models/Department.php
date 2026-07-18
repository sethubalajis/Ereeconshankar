<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    protected $guarded = [];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = static::where('code', 'like', 'DPT-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('DPT-', '', $latest->code)) + 1 : 1;
                $model->code = 'DPT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
}
