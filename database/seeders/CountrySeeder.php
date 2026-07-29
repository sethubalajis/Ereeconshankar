<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CountrySeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            'name' => 'India', 'code' => 'IND', 'status' => true
        ]);
        \Schema::enableForeignKeyConstraints();
    }
}
