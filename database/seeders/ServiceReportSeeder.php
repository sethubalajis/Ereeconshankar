<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceReportSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_reports')->truncate();
        
        $srId = DB::table('service_requests')->where('status', 'resolved')->value('id') ?? 2;

        $reports = [
            ['service_request_id' => $srId, 'resolution_details' => 'Jeans were replaced with a new unit from the warehouse.', 'report_date' => now()->subDays(8)],
        ];
        
        foreach($reports as $r) {
            DB::table('service_reports')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
