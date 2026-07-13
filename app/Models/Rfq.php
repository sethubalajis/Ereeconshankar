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

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->rfq_number)) {
                $latest = static::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $model->rfq_number = 'RFQ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
