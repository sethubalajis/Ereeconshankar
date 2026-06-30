<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->truncate();
        $roles = ['Super Admin', 'Branch Manager', 'Sales Executive', 'Cashier', 'Store Keeper', 'Accountant', 'HR Manager', 'Purchase Officer', 'Marketing Head', 'Customer Care Executive'];
        foreach($roles as $r) {
            DB::table('roles')->insert([
                'name' => $r,
                'status' => true,
            ]);
        }
    }
}
