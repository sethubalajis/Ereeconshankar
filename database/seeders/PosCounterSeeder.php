<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
