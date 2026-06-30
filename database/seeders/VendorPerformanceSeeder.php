<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VendorPerformanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('vendor_performances')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'review_date' => now()->subDays(rand(1, 30)),
                'rating' => rand(1, 9),
                'delivery_score' => rand(1, 9),
                'quality_score' => rand(1, 9),
                'remarks' => $faker->sentence,
            ]);
        }
    }
}
