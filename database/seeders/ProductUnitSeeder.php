<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
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
=======

class ProductUnitSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Meter', 'code' => 'MTR', 'description' => 'Length unit for fabric measurement'],
            ['name' => 'Yard', 'code' => 'YRD', 'description' => 'Length unit commonly used in textiles'],
            ['name' => 'Piece', 'code' => 'PCS', 'description' => 'Finished garment or item count'],
            ['name' => 'Roll', 'code' => 'ROL', 'description' => 'Fabric roll packaging unit'],
            ['name' => 'Kilogram', 'code' => 'KGS', 'description' => 'Weight unit for textile material'],
        ];

        foreach ($rows as $row) {
            DB::table('product_units')->insert([
                'name' => $row['name'],
                'code' => $row['code'],
                'description' => $row['description'],
            ]);
>>>>>>> origin/main
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
