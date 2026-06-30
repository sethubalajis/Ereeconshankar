<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPerformance extends Model
{
    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
