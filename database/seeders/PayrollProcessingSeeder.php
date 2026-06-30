<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PayrollProcessingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('payroll_processings')->insert([
                'payroll_cycle_id' => DB::table('payroll_cycles')->inRandomOrder()->value('id') ?? 1,
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'gross_pay' => rand(1, 9),
                'total_deductions' => rand(1, 9),
                'net_pay' => rand(1, 9),
                'status' => 'active',
            ]);
        }
    }
}
