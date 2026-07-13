<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
