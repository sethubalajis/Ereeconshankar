<?php

namespace App\Services;

use App\Models\PriceList;
use App\Models\PriceListItem;
use Illuminate\Support\Facades\Log;

class PriceListService
{
    /**
     * Determine the applicable price for a product/variant based on the current context.
     *
     * @param int $productId
     * @param int|null $productVariantId
     * @param int|null $customerId
     * @param int|null $customerGroupId
     * @param int|null $membershipPlanId
     * @param int|null $branchId
     * @param int|null $companyId
     * @return PriceListItem|null
     */
    public function getApplicablePriceListItem(
        int $productId,
        ?int $productVariantId = null,
        ?int $membershipPlanId = null,
        ?int $branchId = null,
        ?int $companyId = null
    ): ?PriceListItem {
        $now = now();

        $query = PriceListItem::query()
            ->where('product_id', $productId)
            ->where('status', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now);
            });

        if ($productVariantId) {
            $query->where(function ($q) use ($productVariantId) {
                $q->whereNull('product_variant_id')
                  ->orWhere('product_variant_id', $productVariantId);
            });
        } else {
            $query->whereNull('product_variant_id');
        }

        $query->whereHas('priceList', function ($plQuery) use (
            $now, $membershipPlanId, $branchId, $companyId
        ) {
            $plQuery->where('status', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now);
                });

            if ($companyId) {
                $plQuery->where(function($q) use ($companyId) {
                    $q->whereNull('company_id')->orWhere('company_id', $companyId);
                });
            }

            $plQuery->where(function($q) use ($membershipPlanId) {
                $q->whereNull('membership_plan_id')->orWhere('membership_plan_id', $membershipPlanId);
            })->where(function($q) use ($branchId) {
                $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
            });
        });

        $items = $query->with('priceList')->get();

        if ($items->isEmpty()) {
            return null;
        }

        // Just return the first matched item since priority was removed
        return $items->first();
    }
}
