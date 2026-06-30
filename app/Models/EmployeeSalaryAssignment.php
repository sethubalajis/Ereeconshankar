<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAssignment extends Model
{
    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function salaryStructure() {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }
}
