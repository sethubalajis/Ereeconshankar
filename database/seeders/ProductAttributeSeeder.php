<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductAttributeSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}
