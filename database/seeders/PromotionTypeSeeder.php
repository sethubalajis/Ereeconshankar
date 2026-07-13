<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PromotionTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('promotion_types')->truncate();
        
        $types = [
            ['name' => 'Percentage Discount', 'code' => 'PT-PCT', 'description' => 'A percentage based discount on total cart value.'],
            ['name' => 'Flat Amount Discount', 'code' => 'PT-FLT', 'description' => 'A flat amount discount.'],
            ['name' => 'Free Shipping', 'code' => 'PT-FSHP', 'description' => 'Free shipping on orders above a limit.'],
        ];
        
        foreach($types as $t) {
            DB::table('promotion_types')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
