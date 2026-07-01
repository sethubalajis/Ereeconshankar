<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PromoCodeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('promo_codes')->truncate();
        
        $promos = [
            ['code' => 'WELCOME10', 'discount_percentage' => 10.00, 'discount_amount' => null, 'valid_from' => now()->startOfYear(), 'valid_to' => now()->endOfYear(), 'status' => true],
            ['code' => 'FLAT500', 'discount_percentage' => null, 'discount_amount' => 500.00, 'valid_from' => now()->subDays(5), 'valid_to' => now()->addDays(25), 'status' => true],
            ['code' => 'DIWALI20', 'discount_percentage' => 20.00, 'discount_amount' => null, 'valid_from' => now()->addMonths(3), 'valid_to' => now()->addMonths(4), 'status' => true],
        ];
        
        foreach($promos as $p) {
            DB::table('promo_codes')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
