<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReceiptSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('receipts')->insert([
                'pos_sale_id' => DB::table('pos_sales')->inRandomOrder()->value('id') ?? 1,
                'receipt_number' => $faker->word . ' ' . $faker->word,
                'printed_at' => now(),
            ]);
        }
    }
}
