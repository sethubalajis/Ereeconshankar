<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EWayBill extends Model
{
    protected $guarded = [];

    //
    public function taxInvoice() {
        return $this->belongsTo(TaxInvoice::class, 'tax_invoice_id');
    }
}
