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
        ?int $customerId = null,
        ?int $customerGroupId = null,
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
            $now, $customerId, $customerGroupId, $membershipPlanId, $branchId, $companyId
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

            // Customer specific
            $plQuery->where(function($q) use ($customerId, $customerGroupId, $membershipPlanId, $branchId) {
                $q->whereNull('customer_id')->orWhere('customer_id', $customerId);
            })->where(function($q) use ($customerGroupId) {
                $q->whereNull('customer_group_id')->orWhere('customer_group_id', $customerGroupId);
            })->where(function($q) use ($membershipPlanId) {
                $q->whereNull('membership_plan_id')->orWhere('membership_plan_id', $membershipPlanId);
            })->where(function($q) use ($branchId) {
                $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
            });
        });

        // We want the highest priority price list
        $items = $query->with('priceList')->get();

        if ($items->isEmpty()) {
            return null;
        }

        // Filter out items where the price list has specific context requirements that DO NOT match
        // Since we used orWhere in the DB query, a PriceList with branch_id = NULL will match even if we passed a branch_id.
        // That is correct. BUT if a PriceList has branch_id = 5, and we passed branch_id = 5, it matches.
        // Wait, the DB query logic `whereNull('branch_id')->orWhere('branch_id', $branchId)` is perfect.
        // It means: "either this price list applies to all branches (null), or it applies to THIS branch".

        // Now sort by priority.
        $sortedItems = $items->sortByDesc(function ($item) {
            // Calculate a score or just use priority.
            // A price list with a specific customer_id should ideally have higher priority.
            // But the user requested "Compare Priority" (the `priority` field).
            // So we simply sort by the `priority` field of the PriceList.
            return $item->priceList->priority;
        });

        return $sortedItems->first();
    }
}
