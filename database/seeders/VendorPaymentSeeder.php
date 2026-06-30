<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('vendor_payments')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'purchase_order_id' => DB::table('purchase_orders')->inRandomOrder()->value('id') ?? 1,
                'payment_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'payment_mode_id' => DB::table('payment_modes')->inRandomOrder()->value('id') ?? 1,
                'transaction_reference' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
