<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class FestivalOfferSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('festival_offers')->truncate();
        
        $offers = [
            ['name' => 'Diwali Dhamaka', 'start_date' => now()->addMonths(2), 'end_date' => now()->addMonths(3), 'discount_percentage' => 40.00, 'status' => true],
            ['name' => 'Pongal Special', 'start_date' => now()->subMonths(5), 'end_date' => now()->subMonths(4), 'discount_percentage' => 25.00, 'status' => false],
        ];
        
        foreach($offers as $o) {
            DB::table('festival_offers')->insert($o);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
