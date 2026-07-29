<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('designations')->truncate();
        
        $desigs = [
            ['name' => 'Store Manager', 'code' => 'SMGR', 'description' => 'Manages retail store operations', 'status' => true],
            ['name' => 'Sales Executive', 'code' => 'SALEX', 'description' => 'Handles direct customer sales', 'status' => true],
            ['name' => 'HR Manager', 'code' => 'HRMGR', 'description' => 'Human resources manager', 'status' => true],
        ];
        
        foreach($desigs as $d) {
            DB::table('designations')->insert($d);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
