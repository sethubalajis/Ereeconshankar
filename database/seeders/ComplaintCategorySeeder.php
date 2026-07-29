<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ComplaintCategorySeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('complaint_categories')->truncate();
        
        $categories = [
            ['name' => 'Product Defect', 'code' => 'CC-DEF', 'status' => true],
            ['name' => 'Service Delay', 'code' => 'CC-DLY', 'status' => true],
            ['name' => 'Billing Issue', 'code' => 'CC-BIL', 'status' => true],
            ['name' => 'Staff Behavior', 'code' => 'CC-STF', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('complaint_categories')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
