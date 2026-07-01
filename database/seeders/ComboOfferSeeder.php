<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ComboOfferSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('combo_offers')->truncate();
        
        $offers = [
            ['name' => 'Summer Casual Combo', 'code' => 'CMB-SMR1', 'total_price' => 1999.00, 'start_date' => now()->subDays(30), 'end_date' => now()->addDays(60), 'status' => true],
            ['name' => 'Wedding Premium Set', 'code' => 'CMB-WED2', 'total_price' => 14999.00, 'start_date' => now(), 'end_date' => now()->addMonths(6), 'status' => true],
        ];
        
        foreach($offers as $o) {
            DB::table('combo_offers')->insert($o);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
