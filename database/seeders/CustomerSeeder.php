<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CustomerSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('customers')->truncate();
        
        $groups = DB::table('customer_groups')->pluck('id')->toArray();
        if(empty($groups)) { $groups = [1, 2, 3]; }
        
        $customers = [
            ['customer_group_id' => $groups[0] ?? 1, 'name' => 'Aisha Khan', 'phone' => '9876543210', 'email' => 'aisha.khan@example.com', 'address' => 'Andheri West, Mumbai', 'total_points' => 1500, 'wallet_balance' => 500.00, 'status' => true],
            ['customer_group_id' => $groups[1] ?? 2, 'name' => 'Rahul Sharma', 'phone' => '9876543211', 'email' => 'rahul.s@example.com', 'address' => 'Koramangala, Bangalore', 'total_points' => 450, 'wallet_balance' => 0.00, 'status' => true],
            ['customer_group_id' => $groups[2] ?? 3, 'name' => 'Priya Patel', 'phone' => '9876543212', 'email' => 'priya.p@example.com', 'address' => 'Navrangpura, Ahmedabad', 'total_points' => 0, 'wallet_balance' => 0.00, 'status' => true],
        ];
        
        foreach($customers as $c) {
            DB::table('customers')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
