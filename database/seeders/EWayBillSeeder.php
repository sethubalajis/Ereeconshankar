<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EWayBillSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('e_way_bills')->insert([
                'tax_invoice_id' => DB::table('tax_invoices')->inRandomOrder()->value('id') ?? 1,
                'eway_bill_no' => $faker->word . ' ' . $faker->word,
                'valid_until' => now()->subDays(rand(1, 30)),
                'transporter_id' => 1,
                'vehicle_no' => $faker->word . ' ' . $faker->word,
                'status' => 'active',
            ]);
        }
    }
}
