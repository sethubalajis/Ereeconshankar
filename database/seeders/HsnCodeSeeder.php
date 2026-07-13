<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class HsnCodeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hsn_codes')->truncate();
        
        $hsn = [
            ['hsn_code' => '6205', 'description' => 'Mens or boys shirts'],
            ['hsn_code' => '6204', 'description' => 'Womens or girls suits, ensembles, jackets, blazers, dresses, skirts, divided skirts, trousers, bib and brace overalls, breeches and shorts (other than swimwear)'],
            ['hsn_code' => '6109', 'description' => 'T-shirts, singlets and other vests, knitted or crocheted'],
        ];
        
        foreach($hsn as $h) {
            DB::table('hsn_codes')->insert($h);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
