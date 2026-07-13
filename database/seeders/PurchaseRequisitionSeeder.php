<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseRequisitionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('purchase_requisitions')->insert([
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'department_id' => DB::table('departments')->inRandomOrder()->value('id') ?? 1,
                'subject' => $faker->randomElement(['Office Supplies Request', 'IT Equipment Purchase', 'New Server Requisition', 'Raw Materials for Production', 'Marketing Materials', 'Furniture Replacements']),
                'required_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
                'remarks' => $faker->sentence,
            ]);
        }
    }
}
