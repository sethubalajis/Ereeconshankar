<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_attributes')->truncate();
        
        $attributes = [
            ['name' => 'Fabric', 'code' => 'FAB', 'type' => 'select'],
            ['name' => 'Pattern', 'code' => 'PAT', 'type' => 'select'],
            ['name' => 'Fit', 'code' => 'FIT', 'type' => 'select'],
            ['name' => 'Material', 'code' => 'MAT', 'type' => 'text'],
            ['name' => 'Wash Care', 'code' => 'WSH', 'type' => 'text'],
        ];

        foreach ($attributes as $attr) {
            DB::table('product_attributes')->insert([
                'name' => $attr['name'],
                'code' => $attr['code'],
                'type' => $attr['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
=======
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
>>>>>>> origin/main
    }
}
