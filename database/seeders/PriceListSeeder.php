<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceList;
use Carbon\Carbon;

class PriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priceLists = [
            [
                'name' => 'Standard Retail',
                'code' => 'PL-STD01',
                'discount_percentage' => 0.00,
                'valid_from' => Carbon::now()->startOfYear(),
                'valid_to' => Carbon::now()->addYears(5)->endOfYear(),
                'status' => true,
            ],
            [
                'name' => 'Wholesale Tier 1',
                'code' => 'PL-WS01',
                'discount_percentage' => 10.00,
                'valid_from' => Carbon::now()->startOfYear(),
                'valid_to' => Carbon::now()->addYears(5)->endOfYear(),
                'status' => true,
            ],
            [
                'name' => 'Wholesale Tier 2',
                'code' => 'PL-WS02',
                'discount_percentage' => 15.00,
                'valid_from' => Carbon::now()->startOfYear(),
                'valid_to' => Carbon::now()->addYears(5)->endOfYear(),
                'status' => true,
            ],
            [
                'name' => 'Employee Discount',
                'code' => 'PL-EMP01',
                'discount_percentage' => 20.00,
                'valid_from' => Carbon::now()->startOfYear(),
                'valid_to' => Carbon::now()->addYears(5)->endOfYear(),
                'status' => true,
            ],
            [
                'name' => 'Clearance Sale',
                'code' => 'PL-CLR01',
                'discount_percentage' => 30.00,
                'valid_from' => Carbon::now()->startOfMonth(),
                'valid_to' => Carbon::now()->endOfMonth(),
                'status' => true,
            ],
        ];

        foreach ($priceLists as $priceList) {
            PriceList::updateOrCreate(
                ['code' => $priceList['code']],
                $priceList
            );
        }
    }
}
