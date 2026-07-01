import os

seeders = {}

seeders['ComplaintCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ComplaintCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('complaint_categories')->truncate();
        
        $categories = [
            ['name' => 'Product Defect', 'code' => 'CC-DEF', 'status' => true],
            ['name' => 'Service Delay', 'code' => 'CC-DLY', 'status' => true],
            ['name' => 'Billing Issue', 'code' => 'CC-BIL', 'status' => true],
            ['name' => 'Staff Behavior', 'code' => 'CC-STF', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('complaint_categories')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['CustomerFeedbackSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CustomerFeedbackSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('customer_feedback')->truncate();
        
        $customerId = DB::table('customers')->first()->id ?? 1;
        $branchId = DB::table('branches')->first()->id ?? 1;

        $feedback = [
            ['customer_id' => $customerId, 'branch_id' => $branchId, 'rating' => 5, 'comments' => 'Excellent service and great product quality!', 'feedback_date' => now()->subDays(2)],
            ['customer_id' => $customerId, 'branch_id' => $branchId, 'rating' => 3, 'comments' => 'Average experience, checkout was too slow.', 'feedback_date' => now()->subDays(5)],
        ];
        
        foreach($feedback as $f) {
            DB::table('customer_feedback')->insert($f);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ServiceRequestSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['ComplaintTicketSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ComplaintTicketSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('complaint_tickets')->truncate();
        
        $customerId = DB::table('customers')->first()->id ?? 1;
        $catId = DB::table('complaint_categories')->where('code', 'CC-BIL')->value('id') ?? 1;

        $tickets = [
            ['customer_id' => $customerId, 'complaint_category_id' => $catId, 'ticket_number' => 'TKT-2026-001', 'description' => 'Overcharged on my last purchase due to a missing discount.', 'status' => 'open'],
        ];
        
        foreach($tickets as $t) {
            DB::table('complaint_tickets')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ServiceAssignmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ServiceAssignmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('service_assignments')->truncate();
        
        $srId = DB::table('service_requests')->where('request_number', 'SR-2026-001')->value('id') ?? 1;
        $empId = DB::table('employees')->first()->id ?? 1;

        $assignments = [
            ['service_request_id' => $srId, 'employee_id' => $empId, 'assigned_date' => now()->subDays(2), 'status' => 'assigned'],
        ];
        
        foreach($assignments as $a) {
            DB::table('service_assignments')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ServiceReportSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Service & Complaints seeders created.")
