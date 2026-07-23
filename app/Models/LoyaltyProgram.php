<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class LoyaltyProgram extends Model
{
    protected $guarded = [];

    /**
     * Resolve the most relevant active loyalty program for a customer.
     *
     * This stays compatible when optional targeting columns are absent.
     */
    public static function resolveForCustomer(?Customer $customer): ?self
    {
        $query = static::query()->where('status', true);

        static $columnCache = null;

        if ($columnCache === null) {
            $columnCache = [
                'hasMembershipPlan' => Schema::hasColumn('loyalty_programs', 'membership_plan_id'),
                'hasCustomerGroup' => Schema::hasColumn('loyalty_programs', 'customer_group_id'),
            ];
        }

        $hasMembershipPlan = $columnCache['hasMembershipPlan'];
        $hasCustomerGroup = $columnCache['hasCustomerGroup'];

        if ($customer && ($hasMembershipPlan || $hasCustomerGroup)) {
            $query->where(function ($q) use ($customer, $hasMembershipPlan, $hasCustomerGroup) {
                if ($hasMembershipPlan) {
                    $q->orWhere('membership_plan_id', $customer->membership_plan_id);
                }

                if ($hasCustomerGroup) {
                    $q->orWhere('customer_group_id', $customer->customer_group_id);
                }

                $q->orWhere(function ($q2) use ($hasMembershipPlan, $hasCustomerGroup) {
                    if ($hasMembershipPlan) {
                        $q2->whereNull('membership_plan_id');
                    }

                    if ($hasCustomerGroup) {
                        $q2->whereNull('customer_group_id');
                    }
                });
            });
        }

        return $query->orderByDesc('id')->first();
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = 'LP-' . strtoupper(\Illuminate\Support\Str::random(6));
            }
        });
    }


}
