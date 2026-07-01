<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attribute_values')->truncate();
        DB::table('product_attributes')->truncate();

        $now = now();

        DB::table('product_attributes')->insert([
            ['name' => 'Fabric Composition', 'code' => 'TXT001', 'type' => 'select', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Weave Type', 'code' => 'TXT002', 'type' => 'select', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'GSM', 'code' => 'TXT003', 'type' => 'number', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Color Family', 'code' => 'TXT004', 'type' => 'color', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pattern', 'code' => 'TXT005', 'type' => 'select', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finish', 'code' => 'TXT006', 'type' => 'select', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fabric Width', 'code' => 'TXT007', 'type' => 'text', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'End Use', 'code' => 'TXT008', 'type' => 'select', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
