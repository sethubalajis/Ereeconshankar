<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AccountGroupSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('account_groups')->truncate();
        
        $groups = [
            ['name' => 'Assets', 'code' => 'AST', 'parent_id' => null, 'type' => 'asset'],
            ['name' => 'Liabilities', 'code' => 'LIA', 'parent_id' => null, 'type' => 'liability'],
            ['name' => 'Equity', 'code' => 'EQT', 'parent_id' => null, 'type' => 'equity'],
            ['name' => 'Revenue', 'code' => 'REV', 'parent_id' => null, 'type' => 'revenue'],
            ['name' => 'Expenses', 'code' => 'EXP', 'parent_id' => null, 'type' => 'expense'],
        ];
        
        foreach($groups as $g) {
            DB::table('account_groups')->insert($g);
        }
        
        // Sub-groups
        $astId = DB::table('account_groups')->where('code', 'AST')->value('id');
        DB::table('account_groups')->insert([
            ['name' => 'Current Assets', 'code' => 'C-AST', 'parent_id' => $astId, 'type' => 'asset'],
            ['name' => 'Fixed Assets', 'code' => 'F-AST', 'parent_id' => $astId, 'type' => 'asset'],
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
