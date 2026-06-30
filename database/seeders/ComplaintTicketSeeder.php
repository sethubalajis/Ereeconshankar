<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ComplaintTicketSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('complaint_tickets')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('complaint_tickets')->insert([
                'ticket_number' => 'TKT' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'customer_id' => $faker->numberBetween(1, 10),
                'complaint_category_id' => 1,
                'description' => $faker->paragraph,
                'status' => 'open',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
