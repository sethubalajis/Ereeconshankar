<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BankAccountSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('bank_accounts')->truncate();
        
        $banks = [
            ['bank_name' => 'HDFC Bank', 'account_number' => '50200012345678', 'ifsc_code' => 'HDFC0001234', 'branch_name' => 'Anna Nagar Branch', 'account_type' => 'Current', 'opening_balance' => 250000.00, 'current_balance' => 250000.00, 'is_default' => true],
            ['bank_name' => 'ICICI Bank', 'account_number' => '000105023456', 'ifsc_code' => 'ICIC0000001', 'branch_name' => 'T Nagar Branch', 'account_type' => 'Savings', 'opening_balance' => 50000.00, 'current_balance' => 50000.00, 'is_default' => false],
        ];
        
        foreach($banks as $b) {
            DB::table('bank_accounts')->insert($b);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
