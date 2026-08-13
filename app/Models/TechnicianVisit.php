<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class TechnicianVisit extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function serviceAssignment() {
        return $this->belongsTo(ServiceAssignment::class, 'service_assignment_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
