<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorSeeder extends Seeder {
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendors')->truncate();
        
        $vendors = [
            ['name' => 'Arvind Mills Ltd', 'code' => 'V-ARV', 'contact_person' => 'Ramesh Kumar', 'email' => 'contact@arvind.com', 'phone' => '9876500001', 'address' => 'Naroda Road, Ahmedabad', 'gst_number' => '27AADCB2230M1Z2', 'pan_number' => 'AADCB2230M'],
            ['name' => 'Raymond Apparel', 'code' => 'V-RAY', 'contact_person' => 'Suresh Menon', 'email' => 'sales@raymond.in', 'phone' => '9876500002', 'address' => 'Thane, Maharashtra', 'gst_number' => '27AADCB2230M1Z3', 'pan_number' => 'AADCB2231M'],
            ['name' => 'Vardhman Textiles', 'code' => 'V-VRD', 'contact_person' => 'Anil Sharma', 'email' => 'info@vardhman.com', 'phone' => '9876500003', 'address' => 'Chandigarh Road, Ludhiana', 'gst_number' => '27AADCB2230M1Z4', 'pan_number' => 'AADCB2232M'],
            ['name' => 'SafePack Solutions', 'code' => 'V-SFP', 'contact_person' => 'Neha Gupta', 'email' => 'hello@safepack.com', 'phone' => '9876500004', 'address' => 'Peenya Ind. Area, Bangalore', 'gst_number' => '27AADCB2230M1Z5', 'pan_number' => 'AADCB2233M'],
            ['name' => 'Blue Dart Express', 'code' => 'V-BDX', 'contact_person' => 'Amit Patel', 'email' => 'logistics@bluedart.com', 'phone' => '9876500005', 'address' => 'Sahar Airport Road, Mumbai', 'gst_number' => '27AADCB2230M1Z6', 'pan_number' => 'AADCB2234M'],
        ];

        foreach ($vendors as $v) {
            DB::table('vendors')->insert([
                'name' => $v['name'], 
                'code' => $v['code'], 
                'contact_person' => $v['contact_person'],
                'email' => $v['email'],
                'phone' => $v['phone'],
                'address' => $v['address'],
                'gst_number' => $v['gst_number'],
                'pan_number' => $v['pan_number'],
                'status' => true
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
