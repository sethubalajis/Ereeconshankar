<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
