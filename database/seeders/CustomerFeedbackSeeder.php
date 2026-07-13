<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
