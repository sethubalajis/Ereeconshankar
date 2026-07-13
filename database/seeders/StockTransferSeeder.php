<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
