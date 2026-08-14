<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class ServiceAssignment extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function serviceRequest() {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
