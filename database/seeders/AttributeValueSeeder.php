<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
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
=======
        if (! DB::table('product_attributes')->exists()) {
            $this->call(ProductAttributeSeeder::class);
        }

        DB::table('attribute_values')->truncate();

        $attributeIds = DB::table('product_attributes')->pluck('id', 'code');
        $now = now();

        $valuesByAttributeCode = [
            'TXT001' => ['100% Cotton', 'Cotton-Polyester Blend', '100% Linen', '100% Viscose', 'Organic Cotton'],
            'TXT002' => ['Plain Weave', 'Twill Weave', 'Satin Weave', 'Jacquard Weave', 'Dobby Weave'],
            'TXT003' => ['120', '160', '180', '220', '280'],
            'TXT004' => ['Indigo Blue', 'Maroon', 'Olive Green', 'Mustard', 'Ivory'],
            'TXT005' => ['Solid', 'Striped', 'Checked', 'Floral Print', 'Geometric Print'],
            'TXT006' => ['Mercerized', 'Peached', 'Enzyme Washed', 'Anti-Pilling', 'Wrinkle Resistant'],
            'TXT007' => ['44 in', '48 in', '52 in', '58 in', '60 in'],
            'TXT008' => ['Shirting', 'Suiting', 'Kurti', 'Home Furnishing', 'Uniforms'],
        ];

        $rows = [];

        foreach ($valuesByAttributeCode as $code => $values) {
            $attributeId = $attributeIds[$code] ?? null;

            if (! $attributeId) {
                continue;
            }

            foreach ($values as $value) {
                $rows[] = [
                    'product_attribute_id' => $attributeId,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (! empty($rows)) {
            DB::table('attribute_values')->insert($rows);
>>>>>>> origin/main
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
