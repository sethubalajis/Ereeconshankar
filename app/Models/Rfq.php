<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    protected $guarded = [];

    //
    public function purchaseRequisition() {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }
}
