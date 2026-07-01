import os

seeders = {}

seeders['AssetCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class AssetCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_categories')->truncate();
        
        $categories = [
            ['name' => 'IT Equipment', 'code' => 'IT-EQ', 'status' => true],
            ['name' => 'Furniture & Fixtures', 'code' => 'FURN', 'status' => true],
            ['name' => 'Vehicles', 'code' => 'VEH', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('asset_categories')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AssetSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class AssetSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AssetAllocationSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class AssetAllocationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_allocations')->truncate();
        
        $assetId = DB::table('assets')->where('asset_code', 'AST-IT-001')->value('id') ?? 1;
        $empId = DB::table('employees')->first()->id ?? 1;

        $allocs = [
            ['asset_id' => $assetId, 'employee_id' => $empId, 'allocation_date' => now()->subMonths(11)->format('Y-m-d'), 'return_date' => null, 'status' => 'allocated'],
        ];
        
        foreach($allocs as $a) {
            DB::table('asset_allocations')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AssetMaintenanceSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class AssetMaintenanceSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('asset_maintenances')->truncate();
        
        $assetId = DB::table('assets')->where('asset_code', 'AST-IT-001')->value('id') ?? 1;

        $maint = [
            ['asset_id' => $assetId, 'maintenance_date' => now()->subMonths(1)->format('Y-m-d'), 'cost' => 5000.00, 'description' => 'Annual cleaning and battery check'],
        ];
        
        foreach($maint as $m) {
            DB::table('asset_maintenances')->insert($m);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AssetDepreciationSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Asset seeders created.")
