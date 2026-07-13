<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceRequestSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_requests')->truncate();
        
        $customerId = DB::table('customers')->first()->id ?? 1;

        $requests = [
            ['customer_id' => $customerId, 'request_number' => 'SR-2026-001', 'request_date' => now()->subDays(3), 'issue_description' => 'Need alteration for the shirt purchased yesterday.', 'status' => 'in_progress'],
            ['customer_id' => $customerId, 'request_number' => 'SR-2026-002', 'request_date' => now()->subDays(10), 'issue_description' => 'Requesting a replacement for damaged jeans.', 'status' => 'resolved'],
        ];
        
        foreach($requests as $r) {
            DB::table('service_requests')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
