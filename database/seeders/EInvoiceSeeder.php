<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('e_invoices')->insert([
                'tax_invoice_id' => DB::table('tax_invoices')->inRandomOrder()->value('id') ?? 1,
                'irn' => $faker->word . ' ' . $faker->word,
                'ack_no' => $faker->word . ' ' . $faker->word,
                'ack_date' => now()->subDays(rand(1, 30)),
                'status' => 'active',
            ]);
        }
    }
}
