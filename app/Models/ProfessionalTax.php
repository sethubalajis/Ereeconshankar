<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalTax extends Model
{
    protected $guarded = [];

    //
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }
}
