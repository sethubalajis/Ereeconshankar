import os

seeders = {}

seeders['PosTerminalSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PosTerminalSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pos_terminals')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;
        
        $terminals = [
            ['branch_id' => $branchId, 'terminal_name' => 'Main Cash Desk 1', 'terminal_code' => 'TRM-01', 'status' => true],
            ['branch_id' => $branchId, 'terminal_name' => 'Express Checkout', 'terminal_code' => 'TRM-02', 'status' => true],
        ];
        
        foreach($terminals as $t) {
            DB::table('pos_terminals')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PosCounterSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PosCounterSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pos_counters')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;
        
        $counters = [
            ['branch_id' => $branchId, 'counter_name' => 'Counter A', 'status' => true],
            ['branch_id' => $branchId, 'counter_name' => 'Counter B', 'status' => true],
        ];
        
        foreach($counters as $c) {
            DB::table('pos_counters')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PosSaleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PosSaleSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pos_sales')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;
        $terminalId = DB::table('pos_terminals')->first()->id ?? 1;
        $pmId = DB::table('payment_modes')->first()->id ?? null;
        
        $sales = [
            ['branch_id' => $branchId, 'pos_terminal_id' => $terminalId, 'customer_id' => null, 'invoice_number' => 'INV-POS-1001', 'sale_date' => now()->subDays(2), 'total_amount' => 1500.00, 'tax_amount' => 150.00, 'discount_amount' => 50.00, 'net_amount' => 1600.00, 'payment_mode_id' => $pmId, 'status' => 'completed'],
            ['branch_id' => $branchId, 'pos_terminal_id' => $terminalId, 'customer_id' => null, 'invoice_number' => 'INV-POS-1002', 'sale_date' => now()->subDays(1), 'total_amount' => 2500.00, 'tax_amount' => 250.00, 'discount_amount' => 100.00, 'net_amount' => 2650.00, 'payment_mode_id' => $pmId, 'status' => 'completed'],
        ];
        
        foreach($sales as $s) {
            DB::table('pos_sales')->insert($s);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PosSaleItemSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PosSaleItemSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pos_sale_items')->truncate();
        
        $sales = DB::table('pos_sales')->pluck('id')->toArray();
        if(empty($sales)) { $sales = [1, 2]; }
        $productId = DB::table('products')->first()->id ?? 1;
        
        $items = [
            ['pos_sale_id' => $sales[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 2, 'unit_price' => 750.00, 'discount' => 25.00, 'total_price' => 1450.00],
            ['pos_sale_id' => $sales[1] ?? 2, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 1, 'unit_price' => 2500.00, 'discount' => 100.00, 'total_price' => 2400.00],
        ];
        
        foreach($items as $i) {
            DB::table('pos_sale_items')->insert($i);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PosReturnSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PosReturnSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pos_returns')->truncate();
        
        $sales = DB::table('pos_sales')->pluck('id')->toArray();
        if(empty($sales)) { $sales = [1]; }
        
        $returns = [
            ['pos_sale_id' => $sales[0] ?? 1, 'return_date' => now(), 'return_amount' => 1450.00, 'reason' => 'Customer changed mind / Wrong size', 'status' => 'completed'],
        ];
        
        foreach($returns as $r) {
            DB::table('pos_returns')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Retail POS seeders created.")
