<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductLineSeeder extends Seeder
{
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();
        DB::table('product_lines')->truncate();
        
        $productLines = [
            ['name' => 'Mens Casual Wear', 'code' => 'M-CAS', 'desc' => 'Everyday casual apparel and denim for men.'],
            ['name' => 'Mens Formal Wear', 'code' => 'M-FOR', 'desc' => 'Office wear, suits, and formal trousers for men.'],
            ['name' => 'Womens Ethnic Wear', 'code' => 'W-ETH', 'desc' => 'Traditional sarees, kurtas, and festive wear.'],
            ['name' => 'Womens Western Wear', 'code' => 'W-WES', 'desc' => 'Modern dresses, tops, and casual women apparel.'],
            ['name' => 'Kids & Infants Wear', 'code' => 'KID-IN', 'desc' => 'Comfortable and trendy clothing for children.'],
            ['name' => 'Active & Sportswear', 'code' => 'ACT-SP', 'desc' => 'Athleisure, gym wear, and active lifestyle apparel.'],
        ];

        foreach ($productLines as $line) {
            DB::table('product_lines')->insert([
                'name' => $line['name'],
                'code' => $line['code'],
                'description' => $line['desc'],
                'status' => true,
            ]);
        }
        
        \Schema::enableForeignKeyConstraints();
    }
}
