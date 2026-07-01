<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CustomerGroupSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('customer_groups')->truncate();
        
        $groups = [
            ['name' => 'Gold Members', 'code' => 'CG-GOLD', 'discount_percentage' => 10.00, 'status' => true],
            ['name' => 'Silver Members', 'code' => 'CG-SLVR', 'discount_percentage' => 5.00, 'status' => true],
            ['name' => 'Regular', 'code' => 'CG-REG', 'discount_percentage' => 0.00, 'status' => true],
        ];
        
        foreach($groups as $g) {
            DB::table('customer_groups')->insert($g);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
