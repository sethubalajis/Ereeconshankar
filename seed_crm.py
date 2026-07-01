import os

seeders = {}

seeders['CustomerGroupSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CustomerGroupSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('customer_groups')->truncate();
        
        $groups = [
            ['name' => 'Gold Members', 'code' => 'CG-GOLD', 'discount_percentage' => 10.00, 'status' => true],
            ['name' => 'Silver Members', 'code' => 'CG-SLVR', 'discount_percentage' => 5.00, 'status' => true],
            ['name' => 'Regular', 'code' => 'CG-REG', 'discount_percentage' => 0.00, 'status' => true],
        ];
        
        foreach($groups as $g) {
            DB::table('customer_groups')->insert($g);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['CustomerSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CustomerSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('customers')->truncate();
        
        $groups = DB::table('customer_groups')->pluck('id')->toArray();
        if(empty($groups)) { $groups = [1, 2, 3]; }
        
        $customers = [
            ['customer_group_id' => $groups[0] ?? 1, 'name' => 'Aisha Khan', 'phone' => '9876543210', 'email' => 'aisha.khan@example.com', 'address' => 'Andheri West, Mumbai', 'total_points' => 1500, 'wallet_balance' => 500.00, 'status' => true],
            ['customer_group_id' => $groups[1] ?? 2, 'name' => 'Rahul Sharma', 'phone' => '9876543211', 'email' => 'rahul.s@example.com', 'address' => 'Koramangala, Bangalore', 'total_points' => 450, 'wallet_balance' => 0.00, 'status' => true],
            ['customer_group_id' => $groups[2] ?? 3, 'name' => 'Priya Patel', 'phone' => '9876543212', 'email' => 'priya.p@example.com', 'address' => 'Navrangpura, Ahmedabad', 'total_points' => 0, 'wallet_balance' => 0.00, 'status' => true],
        ];
        
        foreach($customers as $c) {
            DB::table('customers')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['LoyaltyProgramSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class LoyaltyProgramSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('loyalty_programs')->truncate();
        
        $programs = [
            ['name' => 'Retail Rewards', 'code' => 'LP-RET', 'points_per_amount' => 100.00, 'minimum_points_to_redeem' => 500, 'value_per_point' => 1.00, 'status' => true],
            ['name' => 'Festive Bonanza', 'code' => 'LP-FST', 'points_per_amount' => 50.00, 'minimum_points_to_redeem' => 200, 'value_per_point' => 2.00, 'status' => true],
        ];
        
        foreach($programs as $p) {
            DB::table('loyalty_programs')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PromoCodeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("CRM & Loyalty seeders created.")
