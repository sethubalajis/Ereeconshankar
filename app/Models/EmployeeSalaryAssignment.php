<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAssignment extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function salaryStructure() {
        return $this->belongsTo(SalaryStructure::class, 'salary_structure_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
