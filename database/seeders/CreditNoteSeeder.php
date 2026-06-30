<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CreditNoteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('credit_notes')->insert([
                'customer_id' => DB::table('customers')->inRandomOrder()->value('id') ?? 1,
                'note_number' => $faker->word . ' ' . $faker->word,
                'note_date' => now()->subDays(rand(1, 30)),
                'amount' => rand(1, 9),
                'reason' => $faker->sentence,
                'status' => 'active',
            ]);
        }
    }
}
