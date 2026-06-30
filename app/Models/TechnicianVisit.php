<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianVisit extends Model
{
    protected $guarded = [];

    //
    public function serviceAssignment() {
        return $this->belongsTo(ServiceAssignment::class, 'service_assignment_id');
    }
}
