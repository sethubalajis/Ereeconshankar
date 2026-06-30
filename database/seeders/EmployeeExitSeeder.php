<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeExitSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('employee_exits')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'resignation_date' => now()->subDays(rand(1, 30)),
                'last_working_day' => now()->subDays(rand(1, 30)),
                'exit_type' => $faker->word . ' ' . $faker->word,
                'reason' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
