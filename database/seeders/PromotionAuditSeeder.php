<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionAuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PromotionAudit::create([
            'pos_sale_id' => 1, // Assume a mock sale exists
            'promotion_name' => 'B1G1 Summer Special',
            'promotion_type' => 'B1G1',
            'priority' => 100,
            'is_stackable' => false,
            'status' => 'APPLIED',
            'discount_amount' => 500.00,
        ]);

        \App\Models\PromotionAudit::create([
            'pos_sale_id' => 1, 
            'promotion_name' => 'Diwali Combo',
            'promotion_type' => 'COMBO',
            'priority' => 90,
            'is_stackable' => false,
            'status' => 'REJECTED',
            'rejection_reason' => 'Item is locked by a higher priority exclusive offer.',
            'discount_amount' => 0.00,
        ]);
    }
}
