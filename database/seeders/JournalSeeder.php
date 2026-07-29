<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class JournalSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('journals')->truncate();
        
        $journals = [
            ['journal_number' => 'JV-2026-001', 'journal_date' => now()->subDays(5), 'reference' => 'Rent Payment for June', 'total_debit' => 25000.00, 'total_credit' => 25000.00, 'notes' => 'Monthly rent transfer to landlord.', 'status' => 'posted'],
            ['journal_number' => 'JV-2026-002', 'journal_date' => now()->subDays(1), 'reference' => 'Cash Sale Deposit', 'total_debit' => 10000.00, 'total_credit' => 10000.00, 'notes' => 'Deposited cash from POS to HDFC bank.', 'status' => 'draft'],
        ];
        
        foreach($journals as $j) {
            DB::table('journals')->insert($j);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
