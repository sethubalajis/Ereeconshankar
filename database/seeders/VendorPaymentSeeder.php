<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorPaymentSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('vendor_payments')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $pos = DB::table('purchase_orders')->pluck('id')->toArray();
        if(empty($pos)) { $pos = [1, 2, 3]; }
        
        $pmId = DB::table('payment_modes')->first()->id ?? DB::table('payment_modes')->insertGetId([
            'name' => 'Bank Transfer', 'code' => 'NEFT'
        ]);
        
        $payments = [
            ['vendor_id' => $vendors[0] ?? 1, 'purchase_order_id' => $pos[0] ?? 1, 'payment_date' => now()->subDays(2), 'amount' => 125000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889901', 'status' => 'completed'],
            ['vendor_id' => $vendors[1] ?? 2, 'purchase_order_id' => $pos[1] ?? 2, 'payment_date' => now()->subDays(5), 'amount' => 25000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889902', 'status' => 'pending'],
            ['vendor_id' => $vendors[2] ?? 3, 'purchase_order_id' => $pos[2] ?? 3, 'payment_date' => now()->subDays(1), 'amount' => 250000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889903', 'status' => 'completed'],
        ];
        
        foreach($payments as $p) {
            DB::table('vendor_payments')->insert($p);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
