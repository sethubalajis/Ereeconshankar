<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PaymentModeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
