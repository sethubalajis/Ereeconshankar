<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $guarded = [];

    protected $casts = [
        'received_items' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->grn_number)) {
                $lastGrn = self::orderBy('id', 'desc')->first();
                $nextId = $lastGrn ? $lastGrn->id + 1 : 1;
                $model->grn_number = 'GRN-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function purchaseOrder() {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
