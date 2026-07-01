<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_categories')->truncate();
        $categories = [
            ['name' => 'Fabric Suppliers', 'code' => 'FAB-SUP'],
            ['name' => 'Garment Manufacturers', 'code' => 'GAR-MFG'],
            ['name' => 'Packaging Materials', 'code' => 'PKG-MAT'],
            ['name' => 'Logistics & Transport', 'code' => 'LOG-TRN'],
            ['name' => 'IT Equipment', 'code' => 'IT-EQP'],
        ];
        foreach($categories as $c) {
            DB::table('vendor_categories')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
