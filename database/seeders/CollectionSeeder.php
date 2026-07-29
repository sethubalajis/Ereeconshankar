<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CollectionSeeder extends Seeder
{
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();
        DB::table('collections')->truncate();
        
        $collections = [
            [
                'name' => 'Diwali Festive Collection', 
                'code' => 'DIW26', 
                'description' => 'Exclusive ethnic and festive wear for Diwali 2026.',
                'start_date' => now()->addMonths(3)->startOfMonth(),
                'end_date' => now()->addMonths(4)->endOfMonth()
            ],
            [
                'name' => 'Summer Essentials 2026', 
                'code' => 'SUM26', 
                'description' => 'Light, breathable fabrics and casual summer wear.',
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->addMonths(2)->endOfMonth()
            ],
            [
                'name' => 'Bridal Wear Collection', 
                'code' => 'BRD26', 
                'description' => 'Premium bridal sarees and designer wear.',
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear()
            ],
            [
                'name' => 'Winter Warmers', 
                'code' => 'WIN26', 
                'description' => 'Cozy winter clothing and jackets.',
                'start_date' => now()->addMonths(6)->startOfMonth(),
                'end_date' => now()->addMonths(8)->endOfMonth()
            ],
            [
                'name' => 'Monsoon Special', 
                'code' => 'MON26', 
                'description' => 'Quick-dry fabrics and monsoon friendly apparel.',
                'start_date' => now()->addMonths(1)->startOfMonth(),
                'end_date' => now()->addMonths(2)->endOfMonth()
            ],
        ];

        foreach ($collections as $collection) {
            DB::table('collections')->insert(array_merge($collection, ['status' => true]));
        }
        
        \Schema::enableForeignKeyConstraints();
    }
}
