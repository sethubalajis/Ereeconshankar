<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
