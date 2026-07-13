<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PayrollProcessingSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payroll_processings')->truncate();
        
        $cycleId = DB::table('payroll_cycles')->where('status', 'processed')->value('id') ?? 1;
        $empId = DB::table('employees')->first()->id ?? 1;

        $processings = [
            ['payroll_cycle_id' => $cycleId, 'employee_id' => $empId, 'gross_pay' => 50000.00, 'total_deductions' => 2000.00, 'net_pay' => 48000.00, 'status' => 'paid'],
        ];
        
        foreach($processings as $p) {
            DB::table('payroll_processings')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
