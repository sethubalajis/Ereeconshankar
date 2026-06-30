<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $guarded = [];

    //
    public function payrollProcessing() {
        return $this->belongsTo(PayrollProcessing::class, 'payroll_processing_id');
    }
}
