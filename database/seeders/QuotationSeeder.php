<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class QuotationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('quotations')->insert([
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'rfq_id' => DB::table('rfqs')->inRandomOrder()->value('id') ?? 1,
                'quote_date' => now()->subDays(rand(1, 30)),
                'validity_date' => now()->subDays(rand(1, 30)),
                'total_amount' => rand(1, 9),
                'status' => 'active',
            ]);
        }
    }
}
