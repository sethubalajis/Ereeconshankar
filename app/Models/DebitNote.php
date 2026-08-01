<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function purchaseReturn() {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
