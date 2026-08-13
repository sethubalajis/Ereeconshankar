<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function leaveType() {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
