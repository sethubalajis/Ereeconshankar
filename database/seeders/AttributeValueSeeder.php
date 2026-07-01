<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attribute_values')->truncate();
        
        $attributeValues = [
            // Fabric (assuming ID 1)
            ['product_attribute_id' => 1, 'value' => 'Cotton'],
            ['product_attribute_id' => 1, 'value' => 'Silk'],
            ['product_attribute_id' => 1, 'value' => 'Linen'],
            // Pattern (assuming ID 2)
            ['product_attribute_id' => 2, 'value' => 'Solid'],
            ['product_attribute_id' => 2, 'value' => 'Checked'],
            ['product_attribute_id' => 2, 'value' => 'Printed'],
            // Fit (assuming ID 3)
            ['product_attribute_id' => 3, 'value' => 'Slim Fit'],
            ['product_attribute_id' => 3, 'value' => 'Regular Fit'],
            ['product_attribute_id' => 3, 'value' => 'Relaxed Fit'],
            // Material (assuming ID 4)
            ['product_attribute_id' => 4, 'value' => '100% Cotton'],
            ['product_attribute_id' => 4, 'value' => 'Cotton Blend'],
            // Wash Care (assuming ID 5)
            ['product_attribute_id' => 5, 'value' => 'Machine Wash'],
            ['product_attribute_id' => 5, 'value' => 'Dry Clean Only'],
        ];

        foreach ($attributeValues as $val) {
            DB::table('attribute_values')->insert([
                'product_attribute_id' => $val['product_attribute_id'],
                'value' => $val['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
