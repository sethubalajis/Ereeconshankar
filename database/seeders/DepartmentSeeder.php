<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        DB::table('departments')->truncate();
        $deps = ['Sales', 'Purchase', 'Inventory', 'Accounts', 'HR', 'IT', 'Marketing', 'Customer Support', 'Logistics', 'Management'];
        foreach($deps as $index => $d) {
            DB::table('departments')->insert([
                'branch_id' => 1,
                'name' => $d,
                'code' => strtoupper(substr($d, 0, 3)) . $index,
                'status' => true,
            ]);
        }
    }
}
