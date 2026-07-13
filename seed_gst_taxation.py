import os

seeders = {}

seeders['HsnCodeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class HsnCodeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hsn_codes')->truncate();
        
        $hsn = [
            ['hsn_code' => '6205', 'description' => 'Mens or boys shirts'],
            ['hsn_code' => '6204', 'description' => 'Womens or girls suits, ensembles, jackets, blazers, dresses, skirts, divided skirts, trousers, bib and brace overalls, breeches and shorts (other than swimwear)'],
            ['hsn_code' => '6109', 'description' => 'T-shirts, singlets and other vests, knitted or crocheted'],
        ];
        
        foreach($hsn as $h) {
            DB::table('hsn_codes')->insert($h);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TaxGroupSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TaxGroupSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tax_groups')->truncate();
        
        $groups = [
            ['name' => 'GST 5%', 'description' => 'Standard GST at 5% for essential apparel', 'total_tax_percentage' => 5.00],
            ['name' => 'GST 12%', 'description' => 'Standard GST at 12% for apparel', 'total_tax_percentage' => 12.00],
            ['name' => 'GST 18%', 'description' => 'Standard GST at 18%', 'total_tax_percentage' => 18.00],
        ];
        
        foreach($groups as $g) {
            DB::table('tax_groups')->insert($g);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['GstRateSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class GstRateSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gst_rates')->truncate();
        
        $rates = [
            ['cgst_percentage' => 2.50, 'sgst_percentage' => 2.50, 'igst_percentage' => 5.00, 'cess_percentage' => 0.00, 'status' => true],
            ['cgst_percentage' => 6.00, 'sgst_percentage' => 6.00, 'igst_percentage' => 12.00, 'cess_percentage' => 0.00, 'status' => true],
            ['cgst_percentage' => 9.00, 'sgst_percentage' => 9.00, 'igst_percentage' => 18.00, 'cess_percentage' => 0.00, 'status' => true],
        ];
        
        foreach($rates as $r) {
            DB::table('gst_rates')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['GstCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class GstCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gst_categories')->truncate();
        
        $categories = [
            ['name' => 'Exempted Goods', 'code' => 'EXMPT', 'status' => true],
            ['name' => 'Taxable Goods', 'code' => 'TXBL', 'status' => true],
            ['name' => 'Nil Rated', 'code' => 'NIL', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('gst_categories')->insert($c);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['GstReturnSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class GstReturnSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gst_returns')->truncate();
        
        $returns = [
            ['return_type' => 'GSTR-1', 'period_start' => now()->subMonths(2)->startOfMonth(), 'period_end' => now()->subMonths(2)->endOfMonth(), 'filing_date' => now()->subMonths(1)->startOfMonth()->addDays(10), 'arn_number' => 'AA330720042456B', 'status' => 'filed'],
            ['return_type' => 'GSTR-3B', 'period_start' => now()->subMonths(2)->startOfMonth(), 'period_end' => now()->subMonths(2)->endOfMonth(), 'filing_date' => now()->subMonths(1)->startOfMonth()->addDays(19), 'arn_number' => 'AA330720042457C', 'status' => 'filed'],
            ['return_type' => 'GSTR-1', 'period_start' => now()->subMonth()->startOfMonth(), 'period_end' => now()->subMonth()->endOfMonth(), 'filing_date' => null, 'arn_number' => null, 'status' => 'pending'],
        ];
        
        foreach($returns as $r) {
            DB::table('gst_returns')->insert($r);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TaxInvoiceSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TaxInvoiceSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tax_invoices')->truncate();
        
        $salesId = DB::table('pos_sales')->first()->id ?? 1;

        $invoices = [
            ['invoiceable_id' => $salesId, 'invoiceable_type' => 'App\\Models\\PosSale', 'invoice_number' => 'TI-2026-0001', 'invoice_date' => now()->subDays(2), 'taxable_amount' => 1500.00, 'tax_amount' => 150.00, 'total_amount' => 1650.00],
        ];
        
        foreach($invoices as $i) {
            DB::table('tax_invoices')->insert($i);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TdsRecordSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TdsRecordSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tds_records')->truncate();
        
        $vendorId = DB::table('vendors')->first()->id ?? 1;

        $tds = [
            ['section_code' => '194C', 'tds_percentage' => 2.00, 'deductee_id' => $vendorId, 'deductee_type' => 'App\\Models\\Vendor', 'amount' => 2500.00],
        ];
        
        foreach($tds as $t) {
            DB::table('tds_records')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['TcsRecordSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class TcsRecordSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tcs_records')->truncate();
        
        $vendorId = DB::table('vendors')->first()->id ?? 1;

        $tcs = [
            ['section_code' => '206C(1H)', 'tcs_percentage' => 0.10, 'collectee_id' => $vendorId, 'collectee_type' => 'App\\Models\\Vendor', 'amount' => 150.00],
        ];
        
        foreach($tcs as $t) {
            DB::table('tcs_records')->insert($t);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("GST & Taxation seeders created.")
