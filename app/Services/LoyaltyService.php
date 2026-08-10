<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\PosSale;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyPoint;
use Exception;

class LoyaltyService
{
    /**
     * Calculate points that can be earned from a given sale.
     *
     * @param PosSale $posSale
     * @param LoyaltyProgram $program
     * @return int
     */
    public function calculateEarnedPoints(PosSale $posSale, LoyaltyProgram $program): int
    {
        // Simple logic: ₹100 = 1 point, so: (Net Amount / 100) * 1
        // Usually, the program defines this conversion rate.
        $valuePerPoint = (float) ($program->value_per_point ?? 100);
        if ($valuePerPoint <= 0) return 0;
        
        return (int) floor($posSale->net_amount / $valuePerPoint);
    }

    /**
     * Earn loyalty points.
     *
     * @param Customer $customer
     * @param PosSale $posSale
     * @param int $points
     * @throws Exception
     */
    public function earnPoints(Customer $customer, PosSale $posSale, int $points)
    {
        if ($points <= 0) return;

        LoyaltyPoint::create([
            'customer_id' => $customer->id,
            'pos_sale_id' => $posSale->id,
            'branch_id' => $posSale->branch_id,
            'transaction_type' => 'earn',
            'points' => $points,
            'reference' => 'POS Sale #' . $posSale->id,
            'created_by' => auth()->id() ?? 1,
            'transaction_date' => now()->toDateString(),
            'financial_year_id' => $posSale->financial_year_id ?? null,
        ]);

        $customer->increment('total_points', $points);
    }

    /**
     * Redeem loyalty points.
     *
     * @param Customer $customer
     * @param PosSale $posSale
     * @param int $points
     * @throws Exception
     */
    public function redeemPoints(Customer $customer, PosSale $posSale, int $points)
    {
        if ($points <= 0) return;

        if ($customer->total_points < $points) {
            throw new Exception("Insufficient loyalty points for redemption. Available: {$customer->total_points}, Requested: {$points}");
        }

        LoyaltyPoint::create([
            'customer_id' => $customer->id,
            'pos_sale_id' => $posSale->id,
            'branch_id' => $posSale->branch_id,
            'transaction_type' => 'redeem',
            'points' => -$points,
            'reference' => 'Redeemed on POS Sale #' . $posSale->id,
            'created_by' => auth()->id() ?? 1,
            'transaction_date' => now()->toDateString(),
            'financial_year_id' => $posSale->financial_year_id ?? null,
        ]);

        $customer->decrement('total_points', $points);
    }
}
