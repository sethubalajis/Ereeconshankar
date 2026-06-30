<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DebitNoteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('debit_notes')->insert([
                'purchase_return_id' => DB::table('purchase_returns')->inRandomOrder()->value('id') ?? 1,
                'vendor_id' => DB::table('vendors')->inRandomOrder()->value('id') ?? 1,
                'note_number' => $faker->word . ' ' . $faker->word,
                'note_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'status' => 'active',
            ]);
        }
    }
}
