import os

seeders = {}

seeders['VendorAgreementSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['VendorPerformanceSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['VendorPaymentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class VendorPaymentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vendor_payments')->truncate();
        
        $vendors = DB::table('vendors')->pluck('id')->toArray();
        if(empty($vendors)) { $vendors = [1, 2, 3]; }
        
        $pos = DB::table('purchase_orders')->pluck('id')->toArray();
        if(empty($pos)) { $pos = [1, 2, 3]; }
        
        $pmId = DB::table('payment_modes')->first()->id ?? DB::table('payment_modes')->insertGetId([
            'name' => 'Bank Transfer', 'code' => 'NEFT'
        ]);
        
        $payments = [
            ['vendor_id' => $vendors[0] ?? 1, 'purchase_order_id' => $pos[0] ?? 1, 'payment_date' => now()->subDays(2), 'amount' => 125000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889901', 'status' => 'completed'],
            ['vendor_id' => $vendors[1] ?? 2, 'purchase_order_id' => $pos[1] ?? 2, 'payment_date' => now()->subDays(5), 'amount' => 25000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889902', 'status' => 'pending'],
            ['vendor_id' => $vendors[2] ?? 3, 'purchase_order_id' => $pos[2] ?? 3, 'payment_date' => now()->subDays(1), 'amount' => 250000.00, 'payment_mode_id' => $pmId, 'transaction_reference' => 'TXN-NEFT-889903', 'status' => 'completed'],
        ];
        
        foreach($payments as $p) {
            DB::table('vendor_payments')->insert($p);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Vendor Management seeders created.")
