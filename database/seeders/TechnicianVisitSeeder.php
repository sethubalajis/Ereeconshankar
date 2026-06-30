<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TechnicianVisitSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('technician_visits')->insert([
                'service_assignment_id' => DB::table('service_assignments')->inRandomOrder()->value('id') ?? 1,
                'visit_date' => now()->subDays(rand(1, 30)),
                'remarks' => $faker->sentence,
            ]);
        }
    }
}
