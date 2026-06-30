<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProfessionalTaxeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('professional_taxes')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'state_id' => DB::table('states')->inRandomOrder()->value('id') ?? 1,
                'amount' => rand(1, 9),
                'month' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
