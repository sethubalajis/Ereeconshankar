<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branches() {
        return $this->hasMany(Branch::class);
    }
    public function businessUnits() {
        return $this->hasMany(BusinessUnit::class);
    }

    //
    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }
    public function country() {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
