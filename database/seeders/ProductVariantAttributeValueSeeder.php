<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantAttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        $variantIds = DB::table('product_variants')->pluck('id')->all();
        $attributeValueRows = DB::table('attribute_values')->select('id', 'product_attribute_id')->get();

        if (empty($variantIds) || $attributeValueRows->isEmpty()) {
            return;
        }

        DB::table('attribute_value_product_variant')->truncate();

        $valueIdsByAttribute = $attributeValueRows
            ->groupBy('product_attribute_id')
            ->map(fn ($rows) => $rows->pluck('id')->all());

        $rows = [];
        $now = now();

        foreach ($variantIds as $variantId) {
            foreach ($valueIdsByAttribute as $valueIds) {
                $rows[] = [
                    'product_variant_id' => $variantId,
                    'attribute_value_id' => $valueIds[array_rand($valueIds)],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('attribute_value_product_variant')->insert($rows);
    }
}
