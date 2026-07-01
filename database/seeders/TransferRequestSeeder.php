<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TransferRequestSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transfer_requests')->truncate();
        
        $branch1 = DB::table('branches')->first()->id ?? 1;
        $branch2 = DB::table('branches')->skip(1)->first()->id ?? null;
        if (!$branch2) {
            $branch2 = DB::table('branches')->insertGetId([
                'name' => 'Secondary Branch', 'code' => 'BR-02', 'status' => true
            ]);
        }
        
        $requests = [
            ['from_branch_id' => $branch1, 'to_branch_id' => $branch2, 'request_date' => now()->subDays(5), 'status' => 'approved'],
            ['from_branch_id' => $branch2, 'to_branch_id' => $branch1, 'request_date' => now()->subDays(1), 'status' => 'pending'],
        ];
        
        foreach($requests as $r) {
            DB::table('transfer_requests')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
