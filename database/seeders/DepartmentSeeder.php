<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('departments')->truncate();
        
        $branchId = DB::table('branches')->first()->id ?? 1;

        $depts = [
            ['branch_id' => $branchId, 'name' => 'Human Resources', 'code' => 'HR', 'description' => 'HR & Admin', 'status' => true],
            ['branch_id' => $branchId, 'name' => 'Sales & Retail', 'code' => 'SALES', 'description' => 'Store Front Sales', 'status' => true],
            ['branch_id' => $branchId, 'name' => 'IT Support', 'code' => 'IT', 'description' => 'Technical support', 'status' => true],
        ];
        
        foreach($depts as $d) {
            DB::table('departments')->insert($d);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
