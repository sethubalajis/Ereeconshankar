<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
