<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PayrollCycleSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('payroll_cycles')->truncate();
        
        $cycles = [
            ['name' => 'June 2026 Payroll', 'start_date' => '2026-06-01', 'end_date' => '2026-06-30', 'payment_date' => '2026-07-05', 'status' => 'processed'],
            ['name' => 'July 2026 Payroll', 'start_date' => '2026-07-01', 'end_date' => '2026-07-31', 'payment_date' => '2026-08-05', 'status' => 'draft'],
        ];
        
        foreach($cycles as $c) {
            DB::table('payroll_cycles')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
