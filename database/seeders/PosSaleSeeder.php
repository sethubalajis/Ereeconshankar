<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
