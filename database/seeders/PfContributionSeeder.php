<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PfContributionSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('pf_contributions')->truncate();
        
        $empId = DB::table('employees')->first()->id ?? 1;

        $pfs = [
            ['employee_id' => $empId, 'employee_share' => 1800.00, 'employer_share' => 1800.00, 'month' => now()->startOfMonth()->format('Y-m-d')],
        ];
        
        foreach($pfs as $p) {
            DB::table('pf_contributions')->insert($p);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
