<?php

namespace App\Models;

use App\Traits\HasBranch;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TdsRecord extends Model
{
    use HasBranch;

    protected $guarded = [];

    public function payrollProcessing() {
        return $this->belongsTo(PayrollProcessing::class);
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function financialYear() {
        return $this->belongsTo(FinancialYear::class);
    }

    public function getPayrollCycleLabelAttribute(): string
    {
        $fromPayroll = $this->payrollProcessing?->payrollCycle?->name;

        if (! empty($fromPayroll)) {
            return (string) $fromPayroll;
        }

        $referenceDate = $this->created_at ? Carbon::parse($this->created_at)->toDateString() : null;

        if (! $referenceDate) {
            return '-';
        }

        $directCycle = PayrollCycle::query()
            ->where('start_date', '<=', $referenceDate)
            ->where('end_date', '>=', $referenceDate)
            ->orderByDesc('start_date')
            ->first();

        if ($directCycle?->name) {
            return (string) $directCycle->name;
        }

        $employeeId = $this->employee_id;

        if (! $employeeId) {
            return '-';
        }

        $fallbackPayroll = PayrollProcessing::query()
            ->with('payrollCycle')
            ->where('employee_id', $employeeId)
            ->whereHas('payrollCycle', function ($query) use ($referenceDate): void {
                $query->where('start_date', '<=', $referenceDate)
                    ->where('end_date', '>=', $referenceDate);
            })
            ->latest('id')
            ->first();

        return (string) ($fallbackPayroll?->payrollCycle?->name ?? '-');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
