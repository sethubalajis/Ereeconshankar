<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('customer_feedback')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'rating' => rand(1, 9),
                'comments' => $faker->sentence,
                'feedback_date' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
