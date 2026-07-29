<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();
        
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
        }

        \Schema::enableForeignKeyConstraints();
    }
}
