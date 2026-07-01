<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CountrySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            'name' => 'India', 'code' => 'IND', 'status' => true
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
