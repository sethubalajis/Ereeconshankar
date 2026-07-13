import os

seeders = {}

seeders['TransferRequestSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TransferRequestSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_requests')->truncate();
        
        $branch1 = DB::table('branches')->first()->id ?? 1;
        $branch2 = DB::table('branches')->skip(1)->first()->id ?? null;
        if (!$branch2) {
            $branch2 = DB::table('branches')->insertGetId([
                'name' => 'Secondary Branch', 'code' => 'BR-02', 'status' => true
            ]);
        }
        
        $requests = [
            ['from_branch_id' => $branch1, 'to_branch_id' => $branch2, 'request_date' => now()->subDays(5), 'status' => 'approved'],
            ['from_branch_id' => $branch2, 'to_branch_id' => $branch1, 'request_date' => now()->subDays(1), 'status' => 'pending'],
        ];
        
        foreach($requests as $r) {
            DB::table('transfer_requests')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TransferApprovalSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TransferApprovalSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_approvals')->truncate();
        
        $reqs = DB::table('transfer_requests')->pluck('id')->toArray();
        if(empty($reqs)) { $reqs = [1, 2]; }
        $userId = DB::table('users')->first()->id ?? 1;
        
        $approvals = [
            ['transfer_request_id' => $reqs[0] ?? 1, 'approver_id' => $userId, 'approval_date' => now()->subDays(4), 'status' => 'approved'],
        ];
        
        foreach($approvals as $a) {
            DB::table('transfer_approvals')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['StockTransferSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StockTransferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('stock_transfers')->truncate();
        
        $reqs = DB::table('transfer_requests')->where('status', 'approved')->pluck('id')->toArray();
        if(empty($reqs)) { $reqs = [1]; }
        
        $branch1 = DB::table('branches')->first()->id ?? 1;
        $branch2 = DB::table('branches')->skip(1)->first()->id ?? 2;
        
        $transfers = [
            ['transfer_request_id' => $reqs[0] ?? 1, 'from_branch_id' => $branch1, 'to_branch_id' => $branch2, 'transfer_date' => now()->subDays(3), 'vehicle_number' => 'TN-01-AB-1234', 'driver_name' => 'Kumar', 'status' => 'dispatched'],
        ];
        
        foreach($transfers as $t) {
            DB::table('stock_transfers')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TransferItemSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TransferItemSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_items')->truncate();
        
        $transfers = DB::table('stock_transfers')->pluck('id')->toArray();
        if(empty($transfers)) { $transfers = [1]; }
        
        $productId = DB::table('products')->first()->id ?? 1;
        
        $items = [
            ['stock_transfer_id' => $transfers[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 50],
        ];
        
        foreach($items as $i) {
            DB::table('transfer_items')->insert($i);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TransitInventorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TransitInventorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transit_inventories')->truncate();
        
        $transfers = DB::table('stock_transfers')->where('status', 'dispatched')->pluck('id')->toArray();
        if(empty($transfers)) { $transfers = [1]; }
        
        $productId = DB::table('products')->first()->id ?? 1;
        
        $transit = [
            ['stock_transfer_id' => $transfers[0] ?? 1, 'product_id' => $productId, 'product_variant_id' => null, 'quantity' => 50],
        ];
        
        foreach($transit as $t) {
            DB::table('transit_inventories')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TransferReceiptSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TransferReceiptSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_receipts')->truncate();
        
        // Simulating that it's still in transit, so no completed receipts yet, or we can add one
        $transfers = DB::table('stock_transfers')->pluck('id')->toArray();
        if(empty($transfers)) { $transfers = [1]; }
        $userId = DB::table('users')->first()->id ?? 1;
        
        $receipts = [
            ['stock_transfer_id' => $transfers[0] ?? 1, 'receive_date' => now()->subDays(1), 'received_by' => $userId, 'status' => 'received'],
        ];
        
        foreach($receipts as $r) {
            DB::table('transfer_receipts')->insert($r);
        }
        
        // Also update the stock transfer status to received
        DB::table('stock_transfers')->where('id', $transfers[0] ?? 1)->update(['status' => 'received']);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Stock Transfer seeders created.")
