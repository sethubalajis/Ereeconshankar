<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PurchaseOrderSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('purchase_orders')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $orders = [
            ['vendor_id' => $vendors[0] ?? 1, 'branch_id' => 1, 'po_number' => 'PO2026001', 'po_date' => now()->subDays(10), 'expected_delivery_date' => now()->addDays(5), 'status' => 'approved', 'total_amount' => 125000.00],
            ['vendor_id' => $vendors[1] ?? 2, 'branch_id' => 1, 'po_number' => 'PO2026002', 'po_date' => now()->subDays(2), 'expected_delivery_date' => now()->addDays(15), 'status' => 'pending', 'total_amount' => 75000.00],
            ['vendor_id' => $vendors[2] ?? 3, 'branch_id' => 1, 'po_number' => 'PO2026003', 'po_date' => now()->subDays(5), 'expected_delivery_date' => now()->addDays(2), 'status' => 'completed', 'total_amount' => 250000.00],
        ];
        
        foreach($orders as $o) {
            DB::table('purchase_orders')->insert($o);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
