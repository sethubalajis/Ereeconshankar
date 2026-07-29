<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DiscountRuleSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('discount_rules')->truncate();
        
        $ptId = DB::table('promotion_types')->first()->id ?? 1;

        $rules = [
            ['promotion_type_id' => $ptId, 'name' => 'End of Season Sale', 'code' => 'EOSS', 'discount_percentage' => 30.00, 'discount_amount' => null, 'start_date' => now()->subDays(5), 'end_date' => now()->addDays(15), 'min_purchase_amount' => 2000.00, 'status' => true],
            ['promotion_type_id' => $ptId, 'name' => 'Clearance 500 Off', 'code' => 'CLR500', 'discount_percentage' => null, 'discount_amount' => 500.00, 'start_date' => now(), 'end_date' => now()->addDays(30), 'min_purchase_amount' => 5000.00, 'status' => true],
        ];
        
        foreach($rules as $r) {
            DB::table('discount_rules')->insert($r);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
