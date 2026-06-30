<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RfqSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('rfqs')->insert([
                'purchase_requisition_id' => DB::table('purchase_requisitions')->inRandomOrder()->value('id') ?? 1,
                'rfq_date' => now()->subDays(rand(1, 30)),
                'submission_deadline' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
