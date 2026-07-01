<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GstRateSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gst_rates')->truncate();
        
        $rates = [
            ['cgst_percentage' => 2.50, 'sgst_percentage' => 2.50, 'igst_percentage' => 5.00, 'cess_percentage' => 0.00, 'status' => true],
            ['cgst_percentage' => 6.00, 'sgst_percentage' => 6.00, 'igst_percentage' => 12.00, 'cess_percentage' => 0.00, 'status' => true],
            ['cgst_percentage' => 9.00, 'sgst_percentage' => 9.00, 'igst_percentage' => 18.00, 'cess_percentage' => 0.00, 'status' => true],
        ];
        
        foreach($rates as $r) {
            DB::table('gst_rates')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
