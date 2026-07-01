<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
