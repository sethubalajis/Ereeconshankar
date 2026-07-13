import os

seeders = {}

seeders['BusinessUnitSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BusinessUnitSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('business_units')->truncate();
        
        $companyId = DB::table('companies')->first()->id ?? 1;

        $units = [
            ['company_id' => $companyId, 'name' => 'Retail Apparel Division', 'code' => 'BU-RET', 'description' => 'Handles all B2C retail clothing operations.', 'status' => true],
            ['company_id' => $companyId, 'name' => 'Wholesale & B2B', 'code' => 'BU-WHL', 'description' => 'Handles bulk corporate orders and B2B clients.', 'status' => true],
            ['company_id' => $companyId, 'name' => 'E-Commerce Operations', 'code' => 'BU-ECO', 'description' => 'Online store and marketplace fulfillment.', 'status' => true],
        ];
        
        foreach($units as $u) {
            DB::table('business_units')->insert($u);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Business Unit seeder created.")
