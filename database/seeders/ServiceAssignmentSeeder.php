<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ServiceAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('service_assignments')->insert([
                'service_request_id' => DB::table('service_requests')->inRandomOrder()->value('id') ?? 1,
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'assigned_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
