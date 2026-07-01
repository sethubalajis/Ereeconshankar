<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SeasonSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('seasons')->truncate();
        $seasons = ['Summer 2026', 'Winter 2026', 'Autumn 2026', 'Spring 2027', 'Festive Collection'];
        foreach($seasons as $s) {
            DB::table('seasons')->insert([
                'name' => $s, 'code' => strtoupper(substr(str_replace(' ', '', $s), 0, 4)) . rand(10,99), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
