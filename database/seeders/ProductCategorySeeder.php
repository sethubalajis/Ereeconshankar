<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_categories')->truncate();
        $categories = [
            ['name' => 'Mens Wear', 'code' => 'MENS'],
            ['name' => 'Womens Wear', 'code' => 'WMN'],
            ['name' => 'Kids Wear', 'code' => 'KID'],
            ['name' => 'Accessories', 'code' => 'ACC'],
            ['name' => 'Footwear', 'code' => 'FTW'],
        ];
        foreach($categories as $c) {
            DB::table('product_categories')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
