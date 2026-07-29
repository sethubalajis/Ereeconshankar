<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ColorSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('colors')->truncate();
        $colors = [
            ['name' => 'Red', 'code' => 'RED', 'hex' => '#FF0000'],
            ['name' => 'Blue', 'code' => 'BLU', 'hex' => '#0000FF'],
            ['name' => 'Green', 'code' => 'GRN', 'hex' => '#008000'],
            ['name' => 'Black', 'code' => 'BLK', 'hex' => '#000000'],
            ['name' => 'White', 'code' => 'WHT', 'hex' => '#FFFFFF'],
            ['name' => 'Yellow', 'code' => 'YEL', 'hex' => '#FFFF00'],
            ['name' => 'Pink', 'code' => 'PNK', 'hex' => '#FFC0CB'],
            ['name' => 'Purple', 'code' => 'PUR', 'hex' => '#800080'],
            ['name' => 'Grey', 'code' => 'GRY', 'hex' => '#808080'],
            ['name' => 'Navy', 'code' => 'NVY', 'hex' => '#000080']
        ];
        foreach($colors as $c) {
            DB::table('colors')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'hex_code' => $c['hex'], 'status' => true
            ]);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
