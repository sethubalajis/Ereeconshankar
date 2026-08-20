<?php

namespace App\Models;

use App\Traits\HasBranch;

use App\Traits\BelongsToFinancialYear;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    use HasBranch;

    use BelongsToFinancialYear;
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function (self $model): void {
            if (filled($model->requisition_number)) {
                return;
            }

            $companyId = $model->branch->company_id ?? 1;
            $model->requisition_number = \App\Services\DocumentNumberService::generate(
                self::class,
                $companyId,
                $model->branch_id,
                $model->requisition_date ?? now()->format('Y-m-d')
            );
        });
    }

    //
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
