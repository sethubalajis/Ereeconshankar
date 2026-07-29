<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CouponSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('coupons')->truncate();
        
        $coupons = [
            ['code' => 'FIRST50', 'discount_percentage' => 50.00, 'discount_amount' => null, 'start_date' => now()->startOfYear(), 'end_date' => now()->endOfYear(), 'min_purchase_amount' => 1000.00, 'max_usage' => 100, 'current_usage' => 12, 'status' => true],
            ['code' => 'SAVE1000', 'discount_percentage' => null, 'discount_amount' => 1000.00, 'start_date' => now()->subDays(2), 'end_date' => now()->addDays(28), 'min_purchase_amount' => 4999.00, 'max_usage' => 50, 'current_usage' => 5, 'status' => true],
        ];
        
        foreach($coupons as $c) {
            DB::table('coupons')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
