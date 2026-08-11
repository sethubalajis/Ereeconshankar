<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'offer_type' => 'B1G1',
                'priority' => 100,
                'is_stackable' => false,
                'is_exclusive' => true,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'COMBO',
                'priority' => 90,
                'is_stackable' => false,
                'is_exclusive' => true,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'FESTIVAL',
                'priority' => 70,
                'is_stackable' => false,
                'is_exclusive' => true,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'CUSTOMER_GROUP',
                'priority' => 60,
                'is_stackable' => true,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'MEMBERSHIP',
                'priority' => 50,
                'is_stackable' => true,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'COUPON',
                'priority' => 40,
                'is_stackable' => true,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'PROMO_CODE',
                'priority' => 30,
                'is_stackable' => true,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'LOYALTY',
                'priority' => 20,
                'is_stackable' => true,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
            [
                'offer_type' => 'MANUAL',
                'priority' => 10,
                'is_stackable' => false,
                'is_exclusive' => false,
                'best_price_only' => false,
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\PromotionSetting::updateOrCreate(
                ['offer_type' => $setting['offer_type']],
                $setting
            );
        }
    }
}
