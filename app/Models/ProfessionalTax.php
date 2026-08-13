<?php

namespace App\Models;

use App\Traits\HasBranch;

use Illuminate\Database\Eloquent\Model;

class ProfessionalTax extends Model
{
    use HasBranch;

    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
