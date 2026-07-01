<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransferApprovalSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_approvals')->truncate();
        
        $reqs = DB::table('transfer_requests')->pluck('id')->toArray();
        if(empty($reqs)) { $reqs = [1, 2]; }
        $userId = DB::table('users')->first()->id ?? 1;
        
        $approvals = [
            ['transfer_request_id' => $reqs[0] ?? 1, 'approver_id' => $userId, 'approval_date' => now()->subDays(4), 'status' => 'approved'],
        ];
        
        foreach($approvals as $a) {
            DB::table('transfer_approvals')->insert($a);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
