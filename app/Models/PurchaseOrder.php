<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->po_number)) {
                $companyId = $model->branch->company_id ?? 1; // Assuming we can get company_id, maybe we need to fetch it from branch
                $model->po_number = \App\Services\DocumentNumberService::generate(
                    self::class,
                    $companyId,
                    $model->branch_id,
                    $model->po_date ?? now()->format('Y-m-d')
                );
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
