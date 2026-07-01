<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BrandSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->truncate();
        $brands = [
            ['name' => 'Levis', 'code' => 'LEV', 'desc' => 'Premium denim and casual wear.'],
            ['name' => 'Allen Solly', 'code' => 'ALS', 'desc' => 'Office wear and formals.'],
            ['name' => 'FabIndia', 'code' => 'FAB', 'desc' => 'Traditional and ethnic wear.'],
            ['name' => 'Puma', 'code' => 'PMA', 'desc' => 'Sports and activewear.'],
            ['name' => 'Biba', 'code' => 'BBA', 'desc' => 'Womens ethnic wear.'],
        ];
        foreach($brands as $b) {
            DB::table('brands')->insert([
                'name' => $b['name'], 'code' => $b['code'], 'description' => $b['desc'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
