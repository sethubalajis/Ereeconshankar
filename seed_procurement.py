import os

seeders = {}

seeders['VendorCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['VendorSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class VendorSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendors')->truncate();
        $vendors = [
            ['name' => 'Arvind Mills Ltd', 'code' => 'V-ARV', 'email' => 'contact@arvind.com', 'phone' => '9876500001', 'category_id' => 1, 'address' => 'Naroda Road, Ahmedabad'],
            ['name' => 'Raymond Apparel', 'code' => 'V-RAY', 'email' => 'sales@raymond.in', 'phone' => '9876500002', 'category_id' => 2, 'address' => 'Thane, Maharashtra'],
            ['name' => 'Vardhman Textiles', 'code' => 'V-VRD', 'email' => 'info@vardhman.com', 'phone' => '9876500003', 'category_id' => 1, 'address' => 'Chandigarh Road, Ludhiana'],
            ['name' => 'SafePack Solutions', 'code' => 'V-SFP', 'email' => 'hello@safepack.com', 'phone' => '9876500004', 'category_id' => 3, 'address' => 'Peenya Ind. Area, Bangalore'],
            ['name' => 'Blue Dart Express', 'code' => 'V-BDX', 'email' => 'logistics@bluedart.com', 'phone' => '9876500005', 'category_id' => 4, 'address' => 'Sahar Airport Road, Mumbai'],
        ];
        foreach($vendors as $v) {
            DB::table('vendors')->insert([
                'vendor_category_id' => $v['category_id'],
                'name' => $v['name'], 
                'code' => $v['code'], 
                'email' => $v['email'],
                'phone' => $v['phone'],
                'address' => $v['address'],
                'city_id' => 1,
                'state_id' => 1,
                'country_id' => 1,
                'pin_code' => '600001',
                'gst_number' => '27AADCB2230M1Z2',
                'pan_number' => 'AADCB2230M',
                'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['VendorContactSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class VendorContactSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_contacts')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1,2,3,4,5]; }
        
        $contacts = [
            ['name' => 'Ramesh Kumar', 'designation' => 'Sales Head', 'phone' => '9988776655'],
            ['name' => 'Suresh Menon', 'designation' => 'Account Manager', 'phone' => '9988776656'],
            ['name' => 'Anil Sharma', 'designation' => 'Logistics Coordinator', 'phone' => '9988776657'],
        ];
        
        foreach($contacts as $index => $c) {
            DB::table('vendor_contacts')->insert([
                'vendor_id' => $vendors[$index] ?? $vendors[0],
                'name' => $c['name'],
                'designation' => $c['designation'],
                'email' => strtolower(str_replace(' ', '.', $c['name'])) . '@vendor.com',
                'phone' => $c['phone'],
                'is_primary' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Procurement seeders created.")
