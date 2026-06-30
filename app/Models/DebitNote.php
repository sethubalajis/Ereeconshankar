<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    protected $guarded = [];

    //
    public function purchaseReturn() {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
