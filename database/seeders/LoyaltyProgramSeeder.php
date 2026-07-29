<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class LoyaltyProgramSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('loyalty_programs')->truncate();
        
        $programs = [
            ['name' => 'Retail Rewards', 'code' => 'LP-RET', 'points_per_amount' => 100.00, 'minimum_points_to_redeem' => 500, 'value_per_point' => 1.00, 'status' => true],
            ['name' => 'Festive Bonanza', 'code' => 'LP-FST', 'points_per_amount' => 50.00, 'minimum_points_to_redeem' => 200, 'value_per_point' => 2.00, 'status' => true],
        ];
        
        foreach($programs as $p) {
            DB::table('loyalty_programs')->insert($p);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
