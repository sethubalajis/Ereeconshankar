<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GiftVoucherSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('gift_vouchers')->insert([
                'voucher_code' => substr(strtoupper($faker->unique()->bothify('???###')), 0, 3),
                'amount' => rand(1, 9),
                'expiry_date' => now()->subDays(rand(1, 30)),
                'is_redeemed' => true,
            ]);
        }
    }
}
