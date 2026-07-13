import os

seeders = {}

seeders['PromotionTypeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PromotionTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('promotion_types')->truncate();
        
        $types = [
            ['name' => 'Percentage Discount', 'code' => 'PT-PCT', 'description' => 'A percentage based discount on total cart value.'],
            ['name' => 'Flat Amount Discount', 'code' => 'PT-FLT', 'description' => 'A flat amount discount.'],
            ['name' => 'Free Shipping', 'code' => 'PT-FSHP', 'description' => 'Free shipping on orders above a limit.'],
        ];
        
        foreach($types as $t) {
            DB::table('promotion_types')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['DiscountRuleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DiscountRuleSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('discount_rules')->truncate();
        
        $ptId = DB::table('promotion_types')->first()->id ?? 1;

        $rules = [
            ['promotion_type_id' => $ptId, 'name' => 'End of Season Sale', 'code' => 'EOSS', 'discount_percentage' => 30.00, 'discount_amount' => null, 'start_date' => now()->subDays(5), 'end_date' => now()->addDays(15), 'min_purchase_amount' => 2000.00, 'status' => true],
            ['promotion_type_id' => $ptId, 'name' => 'Clearance 500 Off', 'code' => 'CLR500', 'discount_percentage' => null, 'discount_amount' => 500.00, 'start_date' => now(), 'end_date' => now()->addDays(30), 'min_purchase_amount' => 5000.00, 'status' => true],
        ];
        
        foreach($rules as $r) {
            DB::table('discount_rules')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['CouponSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CouponSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('coupons')->truncate();
        
        $coupons = [
            ['code' => 'FIRST50', 'discount_percentage' => 50.00, 'discount_amount' => null, 'start_date' => now()->startOfYear(), 'end_date' => now()->endOfYear(), 'min_purchase_amount' => 1000.00, 'max_usage' => 100, 'current_usage' => 12, 'status' => true],
            ['code' => 'SAVE1000', 'discount_percentage' => null, 'discount_amount' => 1000.00, 'start_date' => now()->subDays(2), 'end_date' => now()->addDays(28), 'min_purchase_amount' => 4999.00, 'max_usage' => 50, 'current_usage' => 5, 'status' => true],
        ];
        
        foreach($coupons as $c) {
            DB::table('coupons')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BuyXGetYOfferSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BuyXGetYOfferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('buy_x_get_y_offers')->truncate();
        
        $p1 = DB::table('products')->first()->id ?? 1;
        $p2 = DB::table('products')->skip(1)->first()->id ?? 2;

        $offers = [
            ['buy_product_id' => $p1, 'get_product_id' => $p1, 'buy_quantity' => 2, 'get_quantity' => 1, 'start_date' => now()->subDays(10), 'end_date' => now()->addDays(20), 'status' => true],
            ['buy_product_id' => $p1, 'get_product_id' => $p2, 'buy_quantity' => 1, 'get_quantity' => 1, 'start_date' => now(), 'end_date' => now()->addDays(15), 'status' => true],
        ];
        
        foreach($offers as $o) {
            DB::table('buy_x_get_y_offers')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['FestivalOfferSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class FestivalOfferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('festival_offers')->truncate();
        
        $offers = [
            ['name' => 'Diwali Dhamaka', 'start_date' => now()->addMonths(2), 'end_date' => now()->addMonths(3), 'discount_percentage' => 40.00, 'status' => true],
            ['name' => 'Pongal Special', 'start_date' => now()->subMonths(5), 'end_date' => now()->subMonths(4), 'discount_percentage' => 25.00, 'status' => false],
        ];
        
        foreach($offers as $o) {
            DB::table('festival_offers')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ComboOfferSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ComboOfferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('combo_offers')->truncate();
        
        $offers = [
            ['name' => 'Summer Casual Combo', 'code' => 'CMB-SMR1', 'total_price' => 1999.00, 'start_date' => now()->subDays(30), 'end_date' => now()->addDays(60), 'status' => true],
            ['name' => 'Wedding Premium Set', 'code' => 'CMB-WED2', 'total_price' => 14999.00, 'start_date' => now(), 'end_date' => now()->addMonths(6), 'status' => true],
        ];
        
        foreach($offers as $o) {
            DB::table('combo_offers')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Promotions seeders created.")
