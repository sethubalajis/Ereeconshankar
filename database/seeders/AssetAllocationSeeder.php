<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AssetAllocationSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('asset_allocations')->truncate();
        
        $assetId = DB::table('assets')->where('asset_code', 'AST-IT-001')->value('id') ?? 1;
        $empId = DB::table('employees')->first()->id ?? 1;

        $allocs = [
            ['asset_id' => $assetId, 'employee_id' => $empId, 'allocation_date' => now()->subMonths(11)->format('Y-m-d'), 'return_date' => null, 'status' => 'allocated'],
        ];
        
        foreach($allocs as $a) {
            DB::table('asset_allocations')->insert($a);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
