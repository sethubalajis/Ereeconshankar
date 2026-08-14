<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function serviceRequest() {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
