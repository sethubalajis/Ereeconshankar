<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class TaxInvoiceSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('tax_invoices')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('tax_invoices')->insert([
                'invoiceable_id' => $faker->numberBetween(1, 10),
                'invoiceable_type' => 'App\Models\PosSale',
                'invoice_number' => 'GST' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'invoice_date' => $faker->date(),
                'taxable_amount' => $faker->randomFloat(2, 1000, 20000),
                'tax_amount' => $faker->randomFloat(2, 50, 2000),
                'total_amount' => $faker->randomFloat(2, 1050, 22000),
            ]);
        }
    }
}
