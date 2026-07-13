import os

seeders = {}

seeders['PaymentModeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PaymentModeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_modes')->truncate();
        
        $modes = [
            ['name' => 'Cash', 'code' => 'CASH', 'is_active' => true],
            ['name' => 'Bank Transfer (NEFT/RTGS)', 'code' => 'BANK', 'is_active' => true],
            ['name' => 'Credit Card', 'code' => 'CC', 'is_active' => true],
            ['name' => 'UPI', 'code' => 'UPI', 'is_active' => true],
        ];
        
        foreach($modes as $m) {
            DB::table('payment_modes')->insert($m);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['AccountGroupSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['ChartOfAccountSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ChartOfAccountSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('chart_of_accounts')->truncate();
        
        $cAstId = DB::table('account_groups')->where('code', 'C-AST')->value('id') ?? 1;
        $revId = DB::table('account_groups')->where('code', 'REV')->value('id') ?? 4;
        $expId = DB::table('account_groups')->where('code', 'EXP')->value('id') ?? 5;

        $coas = [
            ['account_group_id' => $cAstId, 'account_code' => '1001', 'account_name' => 'Cash on Hand', 'account_type' => 'asset', 'opening_balance' => 50000.00, 'current_balance' => 50000.00],
            ['account_group_id' => $cAstId, 'account_code' => '1002', 'account_name' => 'HDFC Current Account', 'account_type' => 'asset', 'opening_balance' => 250000.00, 'current_balance' => 250000.00],
            ['account_group_id' => $revId, 'account_code' => '4001', 'account_name' => 'Sales Revenue', 'account_type' => 'revenue', 'opening_balance' => 0.00, 'current_balance' => 0.00],
            ['account_group_id' => $expId, 'account_code' => '5001', 'account_name' => 'Rent Expense', 'account_type' => 'expense', 'opening_balance' => 0.00, 'current_balance' => 0.00],
        ];
        
        foreach($coas as $coa) {
            DB::table('chart_of_accounts')->insert($coa);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BankAccountSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BankAccountSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bank_accounts')->truncate();
        
        $banks = [
            ['bank_name' => 'HDFC Bank', 'account_number' => '50200012345678', 'ifsc_code' => 'HDFC0001234', 'branch_name' => 'Anna Nagar Branch', 'account_type' => 'Current', 'opening_balance' => 250000.00, 'current_balance' => 250000.00, 'is_default' => true],
            ['bank_name' => 'ICICI Bank', 'account_number' => '000105023456', 'ifsc_code' => 'ICIC0000001', 'branch_name' => 'T Nagar Branch', 'account_type' => 'Savings', 'opening_balance' => 50000.00, 'current_balance' => 50000.00, 'is_default' => false],
        ];
        
        foreach($banks as $b) {
            DB::table('bank_accounts')->insert($b);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['JournalSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class JournalSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('journals')->truncate();
        
        $journals = [
            ['journal_number' => 'JV-2026-001', 'journal_date' => now()->subDays(5), 'reference' => 'Rent Payment for June', 'total_debit' => 25000.00, 'total_credit' => 25000.00, 'notes' => 'Monthly rent transfer to landlord.', 'status' => 'posted'],
            ['journal_number' => 'JV-2026-002', 'journal_date' => now()->subDays(1), 'reference' => 'Cash Sale Deposit', 'total_debit' => 10000.00, 'total_credit' => 10000.00, 'notes' => 'Deposited cash from POS to HDFC bank.', 'status' => 'draft'],
        ];
        
        foreach($journals as $j) {
            DB::table('journals')->insert($j);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['JournalEntrySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class JournalEntrySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('journal_entries')->truncate();
        
        $jv1 = DB::table('journals')->where('journal_number', 'JV-2026-001')->value('id') ?? 1;
        $jv2 = DB::table('journals')->where('journal_number', 'JV-2026-002')->value('id') ?? 2;
        
        $cashAcc = DB::table('chart_of_accounts')->where('account_code', '1001')->value('id') ?? 1;
        $bankAcc = DB::table('chart_of_accounts')->where('account_code', '1002')->value('id') ?? 2;
        $rentAcc = DB::table('chart_of_accounts')->where('account_code', '5001')->value('id') ?? 4;
        
        $entries = [
            // JV 1: Rent Payment (Debit Rent, Credit Bank)
            ['journal_id' => $jv1, 'chart_of_account_id' => $rentAcc, 'description' => 'Rent Expense June', 'debit' => 25000.00, 'credit' => 0.00],
            ['journal_id' => $jv1, 'chart_of_account_id' => $bankAcc, 'description' => 'HDFC Payment', 'debit' => 0.00, 'credit' => 25000.00],
            // JV 2: Cash Deposit (Debit Bank, Credit Cash)
            ['journal_id' => $jv2, 'chart_of_account_id' => $bankAcc, 'description' => 'Bank Deposit', 'debit' => 10000.00, 'credit' => 0.00],
            ['journal_id' => $jv2, 'chart_of_account_id' => $cashAcc, 'description' => 'Cash reduction', 'debit' => 0.00, 'credit' => 10000.00],
        ];
        
        foreach($entries as $e) {
            DB::table('journal_entries')->insert($e);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['LedgerSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class LedgerSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ledgers')->truncate();
        
        $cashAcc = DB::table('chart_of_accounts')->where('account_code', '1001')->value('id') ?? 1;
        $bankAcc = DB::table('chart_of_accounts')->where('account_code', '1002')->value('id') ?? 2;
        $rentAcc = DB::table('chart_of_accounts')->where('account_code', '5001')->value('id') ?? 4;
        
        $ledgers = [
            // Simulating posting JV1
            ['chart_of_account_id' => $rentAcc, 'transaction_date' => now()->subDays(5), 'description' => 'Rent Payment for June', 'debit' => 25000.00, 'credit' => 0.00, 'balance' => 25000.00],
            ['chart_of_account_id' => $bankAcc, 'transaction_date' => now()->subDays(5), 'description' => 'Rent Payment for June', 'debit' => 0.00, 'credit' => 25000.00, 'balance' => 225000.00], // 250k - 25k
        ];
        
        foreach($ledgers as $l) {
            DB::table('ledgers')->insert($l);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Finance & Accounts seeders created.")
