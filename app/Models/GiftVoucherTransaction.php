<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftVoucherTransaction extends Model
{
    protected $guarded = [];

    public function giftVoucher() {
        return $this->belongsTo(GiftVoucher::class);
    }
    public function posSale() {
        return $this->belongsTo(PosSale::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function branch() {
        return $this->belongsTo(Branch::class);
    }
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
