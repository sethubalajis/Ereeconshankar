<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BranchSettingSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('branch_settings')->truncate();
        
        $settings = [
            ['branch_id' => 1, 'setting_key' => 'store_opening_time', 'setting_value' => '09:00 AM'],
            ['branch_id' => 1, 'setting_key' => 'store_closing_time', 'setting_value' => '09:00 PM'],
            ['branch_id' => 1, 'setting_key' => 'contact_email', 'setting_value' => 'chennai.branch@retail.com'],
            ['branch_id' => 1, 'setting_key' => 'contact_phone', 'setting_value' => '+91-44-24567890'],
            ['branch_id' => 1, 'setting_key' => 'manager_name', 'setting_value' => 'Ravi Kumar'],
        ];
        
        foreach($settings as $s) {
            DB::table('branch_settings')->insert($s);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
