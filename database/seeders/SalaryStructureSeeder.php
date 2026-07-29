<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SalaryStructureSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('salary_structures')->truncate();
        
        $structs = [
            ['name' => 'Standard Executive Structure', 'code' => 'STR-EXEC', 'basic_percentage' => 40.00, 'hra_percentage' => 20.00, 'status' => true],
        ];
        
        foreach($structs as $s) {
            DB::table('salary_structures')->insert($s);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
