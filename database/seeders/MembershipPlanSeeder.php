<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MembershipPlan;
use Illuminate\Support\Str;

class MembershipPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Silver Membership',
                'fee' => 500.00,
                'duration_months' => 12,
                'benefits_description' => 'Get 5% discount on all purchases. Special birthday offers.',
                'status' => true,
            ],
            [
                'name' => 'Gold Membership',
                'fee' => 1000.00,
                'duration_months' => 12,
                'benefits_description' => 'Get 10% discount on all purchases. Free home delivery and early access to sales.',
                'status' => true,
            ],
            [
                'name' => 'Platinum Membership',
                'fee' => 2500.00,
                'duration_months' => 24,
                'benefits_description' => 'Get 15% discount on all purchases. Priority customer support, VIP event invites, and exclusive merchandise.',
                'status' => true,
            ],
            [
                'name' => 'Basic Loyalty Plan',
                'fee' => 0.00,
                'duration_months' => 60,
                'benefits_description' => 'Free entry plan to earn loyalty points on every purchase.',
                'status' => true,
            ]
        ];

        foreach ($plans as $plan) {
            MembershipPlan::create($plan);
        }
    }
}
