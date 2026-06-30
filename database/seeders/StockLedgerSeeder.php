<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StockLedgerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('stock_ledgers')->insert([
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'branch_id' => DB::table('branches')->inRandomOrder()->value('id') ?? 1,
                'warehouse_id' => DB::table('warehouses')->inRandomOrder()->value('id') ?? 1,
                'transaction_date' => now()->subDays(rand(1, 30)),
                'opening_qty' => rand(1, 9),
                'inward_qty' => rand(1, 9),
                'outward_qty' => rand(1, 9),
                'closing_qty' => rand(1, 9),
            ]);
        }
    }
}
