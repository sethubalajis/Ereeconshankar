<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ServiceAssignmentSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('service_assignments')->truncate();
        
        $srId = DB::table('service_requests')->where('request_number', 'SR-2026-001')->value('id') ?? 1;
        $empId = DB::table('employees')->first()->id ?? 1;

        $assignments = [
            ['service_request_id' => $srId, 'employee_id' => $empId, 'assigned_date' => now()->subDays(2), 'status' => 'assigned'],
        ];
        
        foreach($assignments as $a) {
            DB::table('service_assignments')->insert($a);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
