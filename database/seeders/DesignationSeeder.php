<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('designations')->truncate();
        
        $desigs = [
            ['name' => 'Store Manager', 'code' => 'SMGR', 'description' => 'Manages retail store operations', 'status' => true],
            ['name' => 'Sales Executive', 'code' => 'SALEX', 'description' => 'Handles direct customer sales', 'status' => true],
            ['name' => 'HR Manager', 'code' => 'HRMGR', 'description' => 'Human resources manager', 'status' => true],
        ];
        
        foreach($desigs as $d) {
            DB::table('designations')->insert($d);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
