<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FabricTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fabric_types')->truncate();
        $fabrics = ['Cotton', 'Silk', 'Polyester', 'Linen', 'Wool', 'Denim', 'Rayon', 'Chiffon'];
        foreach($fabrics as $f) {
            DB::table('fabric_types')->insert([
                'name' => $f, 'code' => strtoupper(substr($f, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
