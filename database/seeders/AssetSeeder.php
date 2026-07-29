<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AssetSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('assets')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;
        $catId1 = DB::table('asset_categories')->where('code', 'IT-EQ')->value('id') ?? 1;
        $catId2 = DB::table('asset_categories')->where('code', 'FURN')->value('id') ?? 2;

        $assets = [
            ['asset_category_id' => $catId1, 'branch_id' => $branchId, 'name' => 'MacBook Pro 16', 'asset_code' => 'AST-IT-001', 'purchase_date' => now()->subMonths(12)->format('Y-m-d'), 'purchase_cost' => 250000.00, 'status' => 'allocated'],
            ['asset_category_id' => $catId1, 'branch_id' => $branchId, 'name' => 'Dell Monitor 27', 'asset_code' => 'AST-IT-002', 'purchase_date' => now()->subMonths(6)->format('Y-m-d'), 'purchase_cost' => 25000.00, 'status' => 'available'],
            ['asset_category_id' => $catId2, 'branch_id' => $branchId, 'name' => 'Ergonomic Office Chair', 'asset_code' => 'AST-FR-001', 'purchase_date' => now()->subMonths(2)->format('Y-m-d'), 'purchase_cost' => 15000.00, 'status' => 'available'],
        ];
        
        foreach($assets as $a) {
            DB::table('assets')->insert($a);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
