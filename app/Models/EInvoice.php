<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class EInvoice extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function taxInvoice() {
        return $this->belongsTo(TaxInvoice::class, 'tax_invoice_id');
    }
}
