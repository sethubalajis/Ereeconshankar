import os

seeders = {}

seeders['ProductUnitSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductUnitSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_units')->truncate();
        
        $units = [
            ['name' => 'Pieces', 'code' => 'PCS', 'description' => 'Single item piece'],
            ['name' => 'Meters', 'code' => 'MTR', 'description' => 'Length in meters for fabrics'],
            ['name' => 'Kilograms', 'code' => 'KG', 'description' => 'Weight in kilograms'],
            ['name' => 'Box', 'code' => 'BOX', 'description' => 'Pre-packed box'],
            ['name' => 'Pairs', 'code' => 'PRS', 'description' => 'Pair of items like shoes/socks'],
        ];
        
        foreach($units as $u) {
            DB::table('product_units')->insert($u);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ProductSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['ProductVariantSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductVariantSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_variants')->truncate();
        
        $products = DB::table('products')->get();
        if($products->isEmpty()) return;

        $variants = [
            ['product_id' => $products[0]->id ?? 1, 'color_id' => 2, 'size_id' => 3, 'sku' => 'SKU-SHCLS01-M-BLU', 'mrp' => 1599.00, 'selling_price' => 1400.00, 'cost_price' => 800.00, 'barcode' => '890123456001', 'is_active' => true],
            ['product_id' => $products[0]->id ?? 1, 'color_id' => 2, 'size_id' => 4, 'sku' => 'SKU-SHCLS01-L-BLU', 'mrp' => 1599.00, 'selling_price' => 1400.00, 'cost_price' => 800.00, 'barcode' => '890123456002', 'is_active' => true],
            ['product_id' => $products[1]->id ?? 2, 'color_id' => 4, 'size_id' => 9, 'sku' => 'SKU-DNSLM02-32-BLK', 'mrp' => 2499.00, 'selling_price' => 2100.00, 'cost_price' => 1200.00, 'barcode' => '890123456003', 'is_active' => true],
            ['product_id' => $products[2]->id ?? 3, 'color_id' => 1, 'size_id' => null, 'sku' => 'SKU-SRKNC03-STD', 'mrp' => 18000.00, 'selling_price' => 17500.00, 'cost_price' => 11000.00, 'barcode' => '890123456004', 'is_active' => true],
        ];
        
        foreach($variants as $v) {
            DB::table('product_variants')->insert($v);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ProductBarcodeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductBarcodeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_barcodes')->truncate();
        
        $variants = DB::table('product_variants')->get();
        if($variants->isEmpty()) return;

        foreach($variants as $v) {
            DB::table('product_barcodes')->insert([
                'product_id' => $v->product_id,
                'product_variant_id' => $v->id,
                'barcode' => $v->barcode,
                'barcode_type' => 'EAN13'
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Product Master seeders created.")
