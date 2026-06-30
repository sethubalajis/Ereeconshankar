<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductCategorySeeder extends Seeder {
    public function run(): void {
        DB::table('product_categories')->truncate();
        $cats = ['Sarees', 'Silk Sarees', 'Cotton Sarees', 'Shirts', 'T-Shirts', 'Jeans', 'Kurtas', 'Kids Wear', 'Footwear', 'Handbags', 'Jewellery', 'Cosmetics', 'Grocery', 'Home Furnishing'];
        foreach ($cats as $c) {
            DB::table('product_categories')->insert([
                'name' => $c, 'code' => strtoupper(substr($c, 0, 3)) . rand(10, 99), 'status' => true
            ]);
        }
    }
}
