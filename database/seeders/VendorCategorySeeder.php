<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorCategorySeeder extends Seeder {
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_categories')->truncate();
        
        $categories = [
            ['name' => 'Fabric Suppliers', 'code' => 'FAB-SUP', 'description' => 'Suppliers providing raw fabric materials.'],
            ['name' => 'Garment Manufacturers', 'code' => 'GAR-MFG', 'description' => 'Contract manufacturers for garments.'],
            ['name' => 'Packaging Materials', 'code' => 'PKG-MAT', 'description' => 'Providers of boxes, tags, and packaging.'],
            ['name' => 'Logistics & Transport', 'code' => 'LOG-TRN', 'description' => 'Shipping, freight, and courier services.'],
            ['name' => 'IT Equipment', 'code' => 'IT-EQP', 'description' => 'Suppliers of hardware and tech equipment.'],
        ];

        foreach ($categories as $c) {
            DB::table('vendor_categories')->insert([
                'name' => $c['name'], 
                'code' => $c['code'], 
                'description' => $c['description']
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
