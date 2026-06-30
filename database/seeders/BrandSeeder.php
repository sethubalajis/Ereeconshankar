<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BrandSeeder extends Seeder {
    public function run(): void {
        DB::table('brands')->truncate();
        $brands = ['Nalli', 'Pothys', 'Kumaran Silks', 'RMKV', 'FabIndia', 'Biba', 'Manyavar', 'Raymond', 'Peter England', 'Allen Solly'];
        foreach ($brands as $b) {
            DB::table('brands')->insert([
                'name' => $b, 'code' => strtoupper(substr($b, 0, 3)), 'status' => true
            ]);
        }
    }
}
