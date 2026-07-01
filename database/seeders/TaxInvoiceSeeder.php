<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TaxInvoiceSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tax_invoices')->truncate();
        
        $salesId = DB::table('pos_sales')->first()->id ?? 1;

        $invoices = [
            ['invoiceable_id' => $salesId, 'invoiceable_type' => 'App\Models\PosSale', 'invoice_number' => 'TI-2026-0001', 'invoice_date' => now()->subDays(2), 'taxable_amount' => 1500.00, 'tax_amount' => 150.00, 'total_amount' => 1650.00],
        ];
        
        foreach($invoices as $i) {
            DB::table('tax_invoices')->insert($i);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
