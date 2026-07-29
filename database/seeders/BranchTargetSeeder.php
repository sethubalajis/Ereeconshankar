<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BranchTargetSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('branch_targets')->truncate();
        
        $targets = [
            ['branch_id' => 1, 'target_month' => now()->startOfMonth(), 'target_amount' => 500000.00, 'achieved_amount' => 450000.00],
            ['branch_id' => 1, 'target_month' => now()->subMonths(1)->startOfMonth(), 'target_amount' => 450000.00, 'achieved_amount' => 480000.00],
            ['branch_id' => 1, 'target_month' => now()->subMonths(2)->startOfMonth(), 'target_amount' => 400000.00, 'achieved_amount' => 390000.00],
        ];
        
        foreach($targets as $t) {
            DB::table('branch_targets')->insert($t);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
