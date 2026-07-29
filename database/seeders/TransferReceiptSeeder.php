<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransferReceiptSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
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
        
        \Schema::enableForeignKeyConstraints();
    }
}
