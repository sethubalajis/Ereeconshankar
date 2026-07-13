<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorPerformanceSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_performances')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $performances = [
            ['vendor_id' => $vendors[0] ?? 1, 'review_date' => now()->subDays(10), 'rating' => 5, 'delivery_score' => 95, 'quality_score' => 98, 'remarks' => 'Excellent fabric quality, on-time delivery.'],
            ['vendor_id' => $vendors[1] ?? 2, 'review_date' => now()->subDays(15), 'rating' => 4, 'delivery_score' => 85, 'quality_score' => 90, 'remarks' => 'Good quality, minor delays in logistics.'],
            ['vendor_id' => $vendors[2] ?? 3, 'review_date' => now()->subDays(5), 'rating' => 3, 'delivery_score' => 70, 'quality_score' => 80, 'remarks' => 'Average service, packaging issues.'],
        ];
        
        foreach($performances as $p) {
            DB::table('vendor_performances')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
