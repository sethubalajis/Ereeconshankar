<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TdsRecord extends Model
{
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
}
