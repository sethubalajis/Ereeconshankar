<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    //
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department() {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function designation() {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
