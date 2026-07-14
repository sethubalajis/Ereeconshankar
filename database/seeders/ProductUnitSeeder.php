<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductUnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_units')->truncate();

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
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
