<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    protected $guarded = [];

    public function company() {
        return $this->belongsTo(Company::class);
    }
    public function city() {
        return $this->belongsTo(City::class);
    }
    public function state() {
        return $this->belongsTo(State::class);
    }
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function departments() {
        return $this->hasMany(Department::class);
    }
    public function costCenters() {
        return $this->hasMany(CostCenter::class);
    }

    //
}
