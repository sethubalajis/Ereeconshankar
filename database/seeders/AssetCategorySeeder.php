<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AssetCategorySeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('asset_categories')->truncate();
        
        $categories = [
            ['name' => 'IT Equipment', 'code' => 'IT-EQ', 'status' => true],
            ['name' => 'Furniture & Fixtures', 'code' => 'FURN', 'status' => true],
            ['name' => 'Vehicles', 'code' => 'VEH', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('asset_categories')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
