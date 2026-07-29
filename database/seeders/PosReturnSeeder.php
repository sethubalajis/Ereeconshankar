<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PosReturnSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('pos_returns')->truncate();
        
        $sales = DB::table('pos_sales')->pluck('id')->toArray();
        if(empty($sales)) { $sales = [1]; }
        
        $returns = [
            ['pos_sale_id' => $sales[0] ?? 1, 'return_date' => now(), 'return_amount' => 1450.00, 'reason' => 'Customer changed mind / Wrong size', 'status' => 'completed'],
        ];
        
        foreach($returns as $r) {
            DB::table('pos_returns')->insert($r);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
