<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTransfer extends Model
{
    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function fromBranch() {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }
    public function toBranch() {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }
}
