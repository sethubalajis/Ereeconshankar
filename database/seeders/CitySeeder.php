<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CitySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        $cities = ['Chennai', 'Coimbatore', 'Madurai', 'Trichy', 'Salem', 'Bangalore', 'Mumbai', 'Delhi', 'Kochi', 'Trivandrum'];
        foreach($cities as $index => $city) {
            DB::table('cities')->insert([
                'state_id' => ($index < 5) ? 1 : (($index == 5) ? 3 : (($index == 6) ? 4 : (($index == 7) ? 5 : 2))),
                'name' => $city, 'code' => strtoupper(substr($city, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
