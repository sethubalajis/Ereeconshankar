<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PaymentModeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_modes')->truncate();
        
        $modes = [
            ['name' => 'Cash', 'code' => 'CASH', 'is_active' => true],
            ['name' => 'Bank Transfer (NEFT/RTGS)', 'code' => 'BANK', 'is_active' => true],
            ['name' => 'Credit Card', 'code' => 'CC', 'is_active' => true],
            ['name' => 'UPI', 'code' => 'UPI', 'is_active' => true],
        ];
        
        foreach($modes as $m) {
            DB::table('payment_modes')->insert($m);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
