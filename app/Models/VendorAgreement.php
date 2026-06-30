<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorAgreement extends Model
{
    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
