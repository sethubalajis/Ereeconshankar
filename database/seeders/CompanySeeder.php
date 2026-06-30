<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CompanySeeder extends Seeder {
    public function run(): void {
        DB::table('companies')->truncate();
        DB::table('companies')->insert([
            'name' => 'The Chennai Silks', 'code' => 'TCS',
            'gst_number' => '33AADCT4422E1Z2', 'pan_number' => 'AADCT4422E',
            'email' => 'info@thechennaisilks.com', 'phone' => '9876543210',
            'website' => 'www.thechennaisilks.com', 'logo' => 'logo.png',
            'address' => 'No. 31, Usman Road, T Nagar',
            'city_id' => 1, 'state_id' => 1, 'country_id' => 1,
            'pin_code' => '600017', 'status' => true,
        ]);
    }
}
