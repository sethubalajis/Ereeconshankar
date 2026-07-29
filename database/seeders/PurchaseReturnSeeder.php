<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseReturnSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $poId = DB::table('purchase_orders')->inRandomOrder()->value('id');
        $grnId = DB::table('grns')->inRandomOrder()->value('id');
        $vendorId = DB::table('vendors')->inRandomOrder()->value('id');
        $branchId = DB::table('branches')->inRandomOrder()->value('id');
        
        if (!$vendorId || !$branchId) {
            return;
        }
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('purchase_returns')->insert([
                'purchase_order_id' => $poId,
                'grn_id' => $grnId,
                'vendor_id' => $vendorId,
                'branch_id' => $branchId,
                'return_date' => now()->subDays(rand(1, 30)),
                'total_amount' => rand(1, 9),
                'reason' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
