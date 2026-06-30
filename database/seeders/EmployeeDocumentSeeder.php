<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EmployeeDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('employee_documents')->insert([
                'employee_id' => DB::table('employees')->inRandomOrder()->value('id') ?? 1,
                'document_type' => $faker->word . ' ' . $faker->word,
                'document_path' => $faker->word . ' ' . $faker->word,
                'is_verified' => true,
            ]);
        }
    }
}
