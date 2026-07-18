<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    protected $guarded = [];

    //
    public function customerGroup() {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
    public function membershipPlan() {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }
}
