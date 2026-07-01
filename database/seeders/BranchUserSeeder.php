<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BranchUserSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branch_users')->truncate();
        
        $userId = DB::table('users')->first()->id ?? 1;

        $users = [
            ['branch_id' => 1, 'user_id' => $userId],
        ];
        
        foreach($users as $u) {
            DB::table('branch_users')->insert($u);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
