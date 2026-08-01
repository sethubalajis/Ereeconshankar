<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function rfq() {
        return $this->belongsTo(Rfq::class, 'rfq_id');
    }
}
