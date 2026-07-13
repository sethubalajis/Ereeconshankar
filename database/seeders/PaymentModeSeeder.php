<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
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
=======

class PaymentModeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_modes')->truncate();

        $now = now();

        DB::table('payment_modes')->insert([
            ['name' => 'Cash', 'code' => 'PM001', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cheque', 'code' => 'PM002', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bank Transfer', 'code' => 'PM003', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'NEFT', 'code' => 'PM004', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'RTGS', 'code' => 'PM005', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'IMPS', 'code' => 'PM006', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'UPI', 'code' => 'PM007', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
>>>>>>> origin/main
    }
}
