import os

seeders = {}

seeders['BranchSettingSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchSettingSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_settings')->truncate();
        
        $settings = [
            ['branch_id' => 1, 'setting_key' => 'store_opening_time', 'setting_value' => '09:00 AM'],
            ['branch_id' => 1, 'setting_key' => 'store_closing_time', 'setting_value' => '09:00 PM'],
            ['branch_id' => 1, 'setting_key' => 'contact_email', 'setting_value' => 'chennai.branch@retail.com'],
            ['branch_id' => 1, 'setting_key' => 'contact_phone', 'setting_value' => '+91-44-24567890'],
            ['branch_id' => 1, 'setting_key' => 'manager_name', 'setting_value' => 'Ravi Kumar'],
        ];
        
        foreach($settings as $s) {
            DB::table('branch_settings')->insert($s);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BranchInventorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchInventorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_inventories')->truncate();
        
        $productId = DB::table('products')->first()->id ?? 1;

        $inventories = [
            ['branch_id' => 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 150],
            ['branch_id' => 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 300],
        ];
        
        foreach($inventories as $inv) {
            DB::table('branch_inventories')->insert($inv);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BranchUserSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchUserSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_users')->truncate();
        
        $userId = DB::table('users')->first()->id ?? 1;

        $users = [
            ['branch_id' => 1, 'user_id' => $userId],
        ];
        
        foreach($users as $u) {
            DB::table('branch_users')->insert($u);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BranchExpenseSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchExpenseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_expenses')->truncate();
        
        $expenses = [
            ['branch_id' => 1, 'expense_date' => now()->subDays(2), 'amount' => 5000.00, 'description' => 'Monthly Electricity Bill', 'status' => 'approved'],
            ['branch_id' => 1, 'expense_date' => now()->subDays(5), 'amount' => 1500.00, 'description' => 'Office Stationery and Printing', 'status' => 'approved'],
            ['branch_id' => 1, 'expense_date' => now()->subDays(1), 'amount' => 3500.00, 'description' => 'Store Maintenance and Cleaning', 'status' => 'pending'],
        ];
        
        foreach($expenses as $e) {
            DB::table('branch_expenses')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BranchTargetSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchTargetSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_targets')->truncate();
        
        $targets = [
            ['branch_id' => 1, 'target_month' => now()->startOfMonth(), 'target_amount' => 500000.00, 'achieved_amount' => 450000.00],
            ['branch_id' => 1, 'target_month' => now()->subMonths(1)->startOfMonth(), 'target_amount' => 450000.00, 'achieved_amount' => 480000.00],
            ['branch_id' => 1, 'target_month' => now()->subMonths(2)->startOfMonth(), 'target_amount' => 400000.00, 'achieved_amount' => 390000.00],
        ];
        
        foreach($targets as $t) {
            DB::table('branch_targets')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Branch Management seeders created.")
