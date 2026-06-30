<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class WarehousSeeder extends Seeder {
    public function run(): void {
        DB::table('warehouses')->truncate();
        $whs = [
            ['name' => 'Chennai Central Warehouse', 'code' => 'WH-CHE'],
            ['name' => 'Coimbatore Hub', 'code' => 'WH-CBE']
        ];
        foreach($whs as $index => $w) {
            DB::table('warehouses')->insert([
                'branch_id' => ($index == 0) ? 1 : 3,
                'name' => $w['name'],
                'code' => $w['code'],
                'address' => $w['name'] . ' Ind. Estate',
                'capacity' => 1000,
                'status' => true,
            ]);
        }
    }
}
