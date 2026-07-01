<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SizeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sizes')->truncate();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34'];
        foreach($sizes as $index => $s) {
            DB::table('sizes')->insert([
                'name' => $s, 'code' => $s, 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
