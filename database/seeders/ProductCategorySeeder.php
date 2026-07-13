<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_categories')->truncate();
        
        // Insert Root Categories
        $roots = [
            ['parent_id' => null, 'name' => 'Men\'s Apparel', 'code' => 'CAT-M', 'description' => 'Clothing for men.', 'status' => true],
            ['parent_id' => null, 'name' => 'Women\'s Apparel', 'code' => 'CAT-W', 'description' => 'Clothing for women.', 'status' => true],
            ['parent_id' => null, 'name' => 'Kid\'s Apparel', 'code' => 'CAT-K', 'description' => 'Clothing for kids.', 'status' => true],
        ];
        
        foreach($roots as $r) {
            DB::table('product_categories')->insert($r);
        }

        $menId = DB::table('product_categories')->where('code', 'CAT-M')->value('id');
        $womenId = DB::table('product_categories')->where('code', 'CAT-W')->value('id');

        // Insert Sub Categories
        $subs = [
            ['parent_id' => $menId, 'name' => 'Men\'s Shirts', 'code' => 'CAT-MS', 'description' => 'Formal and casual shirts for men.', 'status' => true],
            ['parent_id' => $menId, 'name' => 'Men\'s Jeans', 'code' => 'CAT-MJ', 'description' => 'Denim jeans for men.', 'status' => true],
            ['parent_id' => $womenId, 'name' => 'Women\'s Dresses', 'code' => 'CAT-WD', 'description' => 'Dresses for women.', 'status' => true],
            ['parent_id' => $womenId, 'name' => 'Women\'s Tops', 'code' => 'CAT-WT', 'description' => 'Tops and tees for women.', 'status' => true],
        ];

        foreach($subs as $s) {
            DB::table('product_categories')->insert($s);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
