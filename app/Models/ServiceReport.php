<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    protected $guarded = [];

    //
    public function serviceRequest() {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }
}
