<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class InventoryReconciliationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('inventory_reconciliations')->insert([
                'inventory_count_id' => DB::table('inventory_counts')->inRandomOrder()->value('id') ?? 1,
                'product_id' => DB::table('products')->inRandomOrder()->value('id') ?? 1,
                'product_variant_id' => DB::table('product_variants')->inRandomOrder()->value('id') ?? 1,
                'system_qty' => rand(1, 9),
                'physical_qty' => rand(1, 9),
                'variance' => rand(1, 9),
            ]);
        }
    }
}
