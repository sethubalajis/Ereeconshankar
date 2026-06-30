<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReimbursementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('reimbursements')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'expense_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'receipt_path' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
