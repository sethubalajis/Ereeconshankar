<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function paymentMode() {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }
}
