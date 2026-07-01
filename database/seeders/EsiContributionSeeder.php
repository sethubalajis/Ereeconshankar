<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
