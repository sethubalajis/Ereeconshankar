<?php

namespace App\Models;

use App\Traits\HasBranch;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasBranch;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->branch_id) && !empty($model->employee_id)) {
                $emp = Employee::find($model->employee_id);
                if ($emp && $emp->branch_id) {
                    $model->branch_id = $emp->branch_id;
                }
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}