<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ColorSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('colors')->truncate();
        $colors = [
            ['name' => 'Red', 'code' => 'RED'],
            ['name' => 'Blue', 'code' => 'BLU'],
            ['name' => 'Green', 'code' => 'GRN'],
            ['name' => 'Black', 'code' => 'BLK'],
            ['name' => 'White', 'code' => 'WHT'],
            ['name' => 'Yellow', 'code' => 'YEL'],
            ['name' => 'Pink', 'code' => 'PNK'],
            ['name' => 'Purple', 'code' => 'PUR'],
            ['name' => 'Grey', 'code' => 'GRY'],
            ['name' => 'Navy', 'code' => 'NVY']
        ];
        foreach($colors as $c) {
            DB::table('colors')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
