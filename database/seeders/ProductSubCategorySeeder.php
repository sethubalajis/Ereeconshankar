<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductSubCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_sub_categories')->truncate();
        $subs = [
            ['cat_id' => 1, 'name' => 'Shirts', 'code' => 'M-SHR'],
            ['cat_id' => 1, 'name' => 'Trousers', 'code' => 'M-TRU'],
            ['cat_id' => 2, 'name' => 'Sarees', 'code' => 'W-SAR'],
            ['cat_id' => 2, 'name' => 'Kurtas', 'code' => 'W-KUR'],
            ['cat_id' => 3, 'name' => 'Boys T-Shirts', 'code' => 'K-TSH'],
        ];
        foreach($subs as $s) {
            DB::table('product_sub_categories')->insert([
                'product_category_id' => $s['cat_id'], 'name' => $s['name'], 'code' => $s['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
