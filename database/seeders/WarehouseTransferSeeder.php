<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehouseTransferSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('warehouse_transfers')->truncate();
        
        $transfers = [
            ['from_warehouse_id' => 1, 'to_warehouse_id' => 2, 'transfer_date' => now()->subDays(5), 'status' => 'completed'],
            ['from_warehouse_id' => 1, 'to_warehouse_id' => 3, 'transfer_date' => now()->subDays(2), 'status' => 'in_transit'],
            ['from_warehouse_id' => 3, 'to_warehouse_id' => 1, 'transfer_date' => now(), 'status' => 'pending'],
        ];
        
        foreach($transfers as $t) {
            DB::table('warehouse_transfers')->insert($t);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
