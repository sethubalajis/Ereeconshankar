<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $lastVendor = static::orderBy('id', 'desc')->first();
                $nextId = $lastVendor ? $lastVendor->id + 1 : 1;
                $model->code = 'VEN' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class, 'vendor_category_id');
    }
}
