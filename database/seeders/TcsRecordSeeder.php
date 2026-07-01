<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TcsRecordSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tcs_records')->truncate();
        
        $vendorId = DB::table('vendors')->first()->id ?? 1;

        $tcs = [
            ['section_code' => '206C(1H)', 'tcs_percentage' => 0.10, 'collectee_id' => $vendorId, 'collectee_type' => 'App\Models\Vendor', 'amount' => 150.00],
        ];
        
        foreach($tcs as $t) {
            DB::table('tcs_records')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
