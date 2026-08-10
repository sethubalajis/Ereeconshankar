<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CustomerMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::take(10)->get();
        $membershipPlans = MembershipPlan::all();

        if ($customers->isEmpty() || $membershipPlans->isEmpty()) {
            $this->command->info('Please ensure you have customers and membership plans seeded before running this seeder.');
            return;
        }

        foreach ($customers as $customer) {
            $plan = $membershipPlans->random();
            $now = Carbon::now();

            CustomerMembership::updateOrCreate(
                [
                    'customer_id' => $customer->id,
                    'status' => 'Active',
                ],
                [
                    'membership_plan_id' => $plan->id,
                    'membership_number' => 'MEM-' . str_pad($customer->id, 5, '0', STR_PAD_LEFT),
                    'start_date' => $now->copy()->subDays(rand(1, 30)),
                    'end_date' => $now->copy()->addMonths(12),
                    'status' => 'Active',
                ]
            );
            
            // Optionally, create an expired historical membership for some customers
            if (rand(1, 10) > 7) {
                CustomerMembership::create([
                    'customer_id' => $customer->id,
                    'membership_plan_id' => $membershipPlans->random()->id,
                    'membership_number' => 'MEM-OLD-' . str_pad($customer->id, 5, '0', STR_PAD_LEFT),
                    'start_date' => $now->copy()->subMonths(24),
                    'end_date' => $now->copy()->subMonths(12),
                    'status' => 'Expired',
                ]);
            }
        }

        $this->command->info('Customer memberships seeded successfully.');
    }
}
