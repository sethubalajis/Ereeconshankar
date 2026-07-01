<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CostCenterSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cost_centers')->truncate();
        
        $costCenters = [
            ['name' => 'Retail Sales Operations', 'code' => 'CC-RSO', 'desc' => 'All costs related to direct retail sales operations.'],
            ['name' => 'Marketing & Promotions', 'code' => 'CC-MKT', 'desc' => 'Expenditures for advertising, campaigns, and promotions.'],
            ['name' => 'Administration & HR', 'code' => 'CC-ADM', 'desc' => 'General administrative and human resources costs.'],
            ['name' => 'Maintenance & Repairs', 'code' => 'CC-MNT', 'desc' => 'Facility upkeep, utilities, and equipment maintenance.'],
            ['name' => 'IT Infrastructure', 'code' => 'CC-IT', 'desc' => 'Software, hardware, and network infrastructure costs.'],
            ['name' => 'Logistics & Supply Chain', 'code' => 'CC-LOG', 'desc' => 'Transportation, warehousing, and logistics expenses.'],
        ];
        
        foreach ($costCenters as $cc) {
            DB::table('cost_centers')->insert([
                'branch_id' => 1,
                'name' => $cc['name'],
                'code' => $cc['code'],
                'description' => $cc['desc'],
                'status' => true,
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
