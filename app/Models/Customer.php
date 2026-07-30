<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    //
    public function customerGroup() {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
    public function membershipPlan() {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }
    public function state() {
        return $this->belongsTo(State::class, 'state_id');
    }
    public function loyaltyCards() {
        return $this->hasMany(LoyaltyCard::class, 'customer_id');
    }
}
