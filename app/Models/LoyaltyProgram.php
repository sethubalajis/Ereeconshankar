<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyProgram extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = 'LP-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }

    //
    public function customerGroup() {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id');
    }
    public function membershipPlan() {
        return $this->belongsTo(MembershipPlan::class, 'membership_plan_id');
    }
}
