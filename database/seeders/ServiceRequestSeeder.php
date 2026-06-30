<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('service_requests')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'request_number' => $faker->word . ' ' . $faker->word,
                'request_date' => now()->subDays(rand(1, 30)),
                'issue_description' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
