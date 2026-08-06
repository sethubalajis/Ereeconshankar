<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCategory extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $lastCategory = static::orderBy('id', 'desc')->first();
                $nextId = $lastCategory ? $lastCategory->id + 1 : 1;
                $model->code = 'VCAT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'vendor_category_id');
    }
}
