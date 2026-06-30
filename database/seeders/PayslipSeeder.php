<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PayslipSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('payslips')->insert([
                'payroll_processing_id' => DB::table('payroll_processings')->inRandomOrder()->value('id') ?? 1,
                'payslip_number' => $faker->word . ' ' . $faker->word,
                'document_path' => $faker->word . ' ' . $faker->word,
            ]);
        }
    }
}
