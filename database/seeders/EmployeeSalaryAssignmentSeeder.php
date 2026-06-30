<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeSalaryAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('employee_salary_assignments')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'salary_structure_id' => DB::table('salary_structures')->inRandomOrder()->value('id') ?? 1,
                'gross_salary' => rand(1, 9),
                'effective_from' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
