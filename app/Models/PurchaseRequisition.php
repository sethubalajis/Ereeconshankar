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

            $year = now()->format('Y');
            $prefix = "PR-{$year}-";

            $latest = self::query()
                ->where('requisition_number', 'like', $prefix . '%')
                ->select('requisition_number')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextNumber = 1;

            if ($latest?->requisition_number) {
                $lastPart = (int) substr($latest->requisition_number, strlen($prefix));
                $nextNumber = $lastPart + 1;
            }

            $model->requisition_number = $prefix . str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
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
