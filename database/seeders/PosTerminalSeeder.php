<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
