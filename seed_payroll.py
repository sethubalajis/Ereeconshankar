import os

seeders = {}

seeders['SalaryComponentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class SalaryComponentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('salary_components')->truncate();
        
        $components = [
            ['name' => 'Basic Pay', 'type' => 'earning', 'is_taxable' => true, 'status' => true],
            ['name' => 'House Rent Allowance (HRA)', 'type' => 'earning', 'is_taxable' => false, 'status' => true],
            ['name' => 'Special Allowance', 'type' => 'earning', 'is_taxable' => true, 'status' => true],
            ['name' => 'Provident Fund (PF)', 'type' => 'deduction', 'is_taxable' => false, 'status' => true],
            ['name' => 'Professional Tax (PT)', 'type' => 'deduction', 'is_taxable' => false, 'status' => true],
        ];
        
        foreach($components as $c) {
            DB::table('salary_components')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['EmployeeSalaryAssignmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class EmployeeSalaryAssignmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_salary_assignments')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;
        $structId = DB::table('salary_structures')->first()->id ?? 1;

        $assignments = [
            ['employee_id' => $empId, 'salary_structure_id' => $structId, 'gross_salary' => 50000.00, 'effective_from' => '2025-01-01'],
        ];
        
        foreach($assignments as $a) {
            DB::table('employee_salary_assignments')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PfContributionSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PfContributionSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pf_contributions')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;

        $pfs = [
            ['employee_id' => $empId, 'employee_share' => 1800.00, 'employer_share' => 1800.00, 'month' => now()->startOfMonth()->format('Y-m-d')],
        ];
        
        foreach($pfs as $p) {
            DB::table('pf_contributions')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['EsiContributionSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class EsiContributionSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('esi_contributions')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;

        $esis = [
            ['employee_id' => $empId, 'employee_share' => 375.00, 'employer_share' => 1625.00, 'month' => now()->startOfMonth()->format('Y-m-d')],
        ];
        
        foreach($esis as $e) {
            DB::table('esi_contributions')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ProfessionalTaxSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProfessionalTaxSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('professional_taxes')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;
        
        // Ensure state exists or use null if allowed, but schema says state_id. 
        // We'll just grab the first state or hardcode a fake one if needed.
        $stateId = null;
        if (DB::getSchemaBuilder()->hasTable('states')) {
            $stateId = DB::table('states')->first()->id ?? null;
        }

        $pts = [
            ['employee_id' => $empId, 'state_id' => $stateId, 'amount' => 200.00, 'month' => now()->startOfMonth()->format('Y-m-d')],
        ];
        
        foreach($pts as $p) {
            DB::table('professional_taxes')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PayrollProcessingSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Payroll seeders created.")
