<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollProcessing extends Model
{
    protected $guarded = [];

    //
    public function payrollCycle() {
        return $this->belongsTo(PayrollCycle::class, 'payroll_cycle_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
