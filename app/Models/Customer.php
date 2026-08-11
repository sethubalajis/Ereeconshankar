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

    public function customerMemberships() {
        return $this->hasMany(CustomerMembership::class, 'customer_id');
    }

    /**
     * Get the active membership for the customer based on dates and status.
     */
    public function activeMembership() {
        $now = \Illuminate\Support\Carbon::now()->toDateString();
        return $this->hasOne(CustomerMembership::class, 'customer_id')
            ->where('status', 'Active')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->latest('id');
    }
}
