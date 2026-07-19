<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $latest = static::where('code', 'like', 'AG-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('AG-', '', $latest->code)) + 1 : 1;
                $model->code = 'AG-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
