<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAssignment extends Model
{
    protected $guarded = [];

    //
    public function serviceRequest() {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
