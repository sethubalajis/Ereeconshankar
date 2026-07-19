<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->employee_code)) {
                $latest = static::where('employee_code', 'like', 'EMP-%')->latest('id')->first();
                $next = $latest ? intval(str_replace('EMP-', '', $latest->employee_code)) + 1 : 1;
                $model->employee_code = 'EMP-' . str_pad($next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

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
