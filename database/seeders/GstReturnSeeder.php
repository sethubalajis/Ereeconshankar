<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GstReturnSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gst_returns')->truncate();
        
        $returns = [
            ['return_type' => 'GSTR-1', 'period_start' => now()->subMonths(2)->startOfMonth(), 'period_end' => now()->subMonths(2)->endOfMonth(), 'filing_date' => now()->subMonths(1)->startOfMonth()->addDays(10), 'arn_number' => 'AA330720042456B', 'status' => 'filed'],
            ['return_type' => 'GSTR-3B', 'period_start' => now()->subMonths(2)->startOfMonth(), 'period_end' => now()->subMonths(2)->endOfMonth(), 'filing_date' => now()->subMonths(1)->startOfMonth()->addDays(19), 'arn_number' => 'AA330720042457C', 'status' => 'filed'],
            ['return_type' => 'GSTR-1', 'period_start' => now()->subMonth()->startOfMonth(), 'period_end' => now()->subMonth()->endOfMonth(), 'filing_date' => null, 'arn_number' => null, 'status' => 'pending'],
        ];
        
        foreach($returns as $r) {
            DB::table('gst_returns')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
