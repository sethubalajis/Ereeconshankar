import os

def write_seeder(name, content):
    path = os.path.join('database', 'seeders', name)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)

seeders = {}

seeders['DesignationSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::table('designations')->truncate();
        DB::table('designations')->insert(['name' => 'Manager', 'code' => 'MGR', 'status' => true]);
    }
}
"""

seeders['PurchaseOrderSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class PurchaseOrderSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('purchase_orders')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('purchase_orders')->insert([
                'vendor_id' => $faker->numberBetween(1, 10),
                'branch_id' => $faker->numberBetween(1, 3),
                'po_number' => 'PO' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'po_date' => $faker->date(),
                'expected_delivery_date' => $faker->date(),
                'status' => 'approved',
                'total_amount' => $faker->randomFloat(2, 5000, 50000),
            ]);
        }
    }
}
"""

seeders['StockSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class StockSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('stocks')->truncate();
        for ($i = 1; $i <= 15; $i++) {
            DB::table('stocks')->insert([
                'product_id' => $i,
                'warehouse_id' => $faker->numberBetween(1, 2),
                'quantity' => $faker->numberBetween(50, 500),
                'batch_number' => 'BAT' . $faker->numerify('######'),
            ]);
        }
    }
}
"""

seeders['PosSaleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class PosSaleSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('pos_sales')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('pos_sales')->insert([
                'branch_id' => $faker->numberBetween(1, 3),
                'customer_id' => $faker->numberBetween(1, 10),
                'invoice_number' => 'INV' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sale_date' => $faker->date(),
                'total_amount' => $faker->randomFloat(2, 1000, 20000),
                'payment_status' => 'paid',
                'status' => 'completed',
            ]);
        }
    }
}
"""

seeders['PaymentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class PaymentSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('payments')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('payments')->insert([
                'branch_id' => $faker->numberBetween(1, 3),
                'customer_id' => $faker->numberBetween(1, 10),
                'receipt_number' => 'REC' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'payment_date' => $faker->date(),
                'amount' => $faker->randomFloat(2, 1000, 20000),
                'payment_method' => $faker->randomElement(['cash', 'card', 'upi']),
                'reference_number' => 'UPI' . $faker->numerify('##########'),
            ]);
        }
    }
}
"""

seeders['TaxInvoiceSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class TaxInvoiceSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('tax_invoices')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('tax_invoices')->insert([
                'branch_id' => $faker->numberBetween(1, 3),
                'customer_id' => $faker->numberBetween(1, 10),
                'invoice_number' => 'GST' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'invoice_date' => $faker->date(),
                'total_taxable_amount' => $faker->randomFloat(2, 1000, 20000),
                'total_tax_amount' => $faker->randomFloat(2, 50, 2000),
                'net_amount' => $faker->randomFloat(2, 1050, 22000),
                'status' => 'issued',
            ]);
        }
    }
}
"""

seeders['AssetSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class AssetSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('assets')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('assets')->insert([
                'name' => 'Asset ' . $faker->word,
                'code' => 'AST' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'branch_id' => $faker->numberBetween(1, 3),
                'purchase_date' => $faker->date(),
                'purchase_cost' => $faker->randomFloat(2, 10000, 100000),
                'status' => 'active',
            ]);
        }
    }
}
"""

seeders['ComplaintTicketSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class ComplaintTicketSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('complaint_tickets')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('complaint_tickets')->insert([
                'ticket_number' => 'TKT' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_id' => $faker->numberBetween(1, 10),
                'subject' => $faker->sentence(3),
                'description' => $faker->paragraph,
                'status' => 'open',
                'priority' => $faker->randomElement(['low', 'medium', 'high']),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
"""

for k, v in seeders.items():
    write_seeder(k, v)

print("Seeders generation part 3 complete.")
