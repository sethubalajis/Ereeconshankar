<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StyleSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('styles')->truncate();
        $styles = ['Casual', 'Formal', 'Party Wear', 'Ethnic', 'Sportswear', 'Lounge Wear'];
        foreach($styles as $s) {
            DB::table('styles')->insert([
                'name' => $s, 'code' => strtoupper(substr(str_replace(' ', '', $s), 0, 4)), 'status' => true
            ]);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
