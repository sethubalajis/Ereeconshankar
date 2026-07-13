<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorAgreementSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_agreements')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $agreements = [
            ['vendor_id' => $vendors[0] ?? 1, 'title' => 'Annual Fabric Supply Agreement', 'start_date' => now()->startOfYear(), 'end_date' => now()->endOfYear(), 'document_path' => 'agreements/arvind_2026.pdf', 'status' => 'active'],
            ['vendor_id' => $vendors[1] ?? 2, 'title' => 'Premium Garments Sourcing Contract', 'start_date' => now()->startOfYear(), 'end_date' => now()->endOfYear(), 'document_path' => 'agreements/raymond_2026.pdf', 'status' => 'active'],
            ['vendor_id' => $vendors[2] ?? 3, 'title' => 'National Logistics SLA', 'start_date' => now()->subMonths(6), 'end_date' => now()->addMonths(6), 'document_path' => 'agreements/bluedart_sla.pdf', 'status' => 'active'],
        ];
        
        foreach($agreements as $a) {
            DB::table('vendor_agreements')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
