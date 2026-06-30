<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    protected $guarded = [];

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function posTerminal() {
        return $this->belongsTo(PosTerminal::class, 'pos_terminal_id');
    }
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function paymentMode() {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }
}
