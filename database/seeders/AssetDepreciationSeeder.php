<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AssetDepreciationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_depreciations')->truncate();
        
        $assetId = DB::table('assets')->where('asset_code', 'AST-IT-001')->value('id') ?? 1;

        $deps = [
            ['asset_id' => $assetId, 'depreciation_date' => now()->startOfYear()->format('Y-m-d'), 'amount' => 50000.00, 'current_value' => 200000.00],
        ];
        
        foreach($deps as $d) {
            DB::table('asset_depreciations')->insert($d);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
