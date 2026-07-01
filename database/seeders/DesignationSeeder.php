<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('designations')->truncate();
        $desigs = ['General Manager', 'Branch Manager', 'Sales Executive', 'Cashier', 'Store Keeper', 'Accountant', 'HR Manager', 'Purchase Officer', 'Marketing Head', 'Customer Care Executive'];
        foreach($desigs as $index => $d) {
            DB::table('designations')->insert([
                'name' => $d,
                'code' => strtoupper(substr(str_replace(' ', '', $d), 0, 4)) . $index,
                'status' => true,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
