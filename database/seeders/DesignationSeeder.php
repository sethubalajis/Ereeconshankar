<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::table('designations')->truncate();
        DB::table('designations')->insert(['name' => 'Manager', 'code' => 'MGR', 'status' => true]);
    }
}
