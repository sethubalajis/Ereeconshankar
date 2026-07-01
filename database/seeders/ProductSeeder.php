<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        
        $brandId = DB::table('brands')->first()->id ?? 1;
        $catId = DB::table('product_categories')->first()->id ?? 1;
        $subCatId = DB::table('product_sub_categories')->first()->id ?? 1;
        $unitId = DB::table('product_units')->where('code', 'PCS')->first()->id ?? 1;
        $hsnId = DB::table('hsn_codes')->first()->id ?? null;
        $taxId = DB::table('tax_groups')->first()->id ?? null;

        $products = [
            [
                'brand_id' => $brandId, 'product_category_id' => $catId, 'product_sub_category_id' => $subCatId, 'product_unit_id' => $unitId,
                'hsn_code_id' => $hsnId, 'tax_group_id' => $taxId,
                'name' => 'Classic Cotton Shirt', 'code' => 'SH-CLS-01', 'description' => 'Premium casual wear shirt.', 'is_active' => true
            ],
            [
                'brand_id' => $brandId, 'product_category_id' => $catId, 'product_sub_category_id' => $subCatId, 'product_unit_id' => $unitId,
                'hsn_code_id' => $hsnId, 'tax_group_id' => $taxId,
                'name' => 'Denim Jeans Slim Fit', 'code' => 'DN-SLM-02', 'description' => 'Slim fit stretchable jeans.', 'is_active' => true
            ],
            [
                'brand_id' => $brandId, 'product_category_id' => $catId, 'product_sub_category_id' => $subCatId, 'product_unit_id' => $unitId,
                'hsn_code_id' => $hsnId, 'tax_group_id' => $taxId,
                'name' => 'Silk Saree Kanchipuram', 'code' => 'SR-KNC-03', 'description' => 'Authentic handwoven silk saree.', 'is_active' => true
            ],
        ];
        
        foreach($products as $p) {
            DB::table('products')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
