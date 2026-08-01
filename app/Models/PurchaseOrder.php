<?php

namespace App\Models;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->po_number)) {
                $lastPo = self::orderBy('id', 'desc')->first();
                $nextId = $lastPo ? $lastPo->id + 1 : 1;
                $model->po_number = 'PO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    //
    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function items() {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
