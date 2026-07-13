<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TdsRecordSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tds_records')->truncate();
        
        $vendorId = DB::table('vendors')->first()->id ?? 1;

        $tds = [
            ['section_code' => '194C', 'tds_percentage' => 2.00, 'deductee_id' => $vendorId, 'deductee_type' => 'App\Models\Vendor', 'amount' => 2500.00],
        ];
        
        foreach($tds as $t) {
            DB::table('tds_records')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
