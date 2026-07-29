<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AssetMaintenanceSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('asset_maintenances')->truncate();
        
        $assetId = DB::table('assets')->where('asset_code', 'AST-IT-001')->value('id') ?? 1;

        $maint = [
            ['asset_id' => $assetId, 'maintenance_date' => now()->subMonths(1)->format('Y-m-d'), 'cost' => 5000.00, 'description' => 'Annual cleaning and battery check'],
        ];
        
        foreach($maint as $m) {
            DB::table('asset_maintenances')->insert($m);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
