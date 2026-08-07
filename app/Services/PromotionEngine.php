<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Models\BuyXGetYOffer;
use App\Models\ComboOffer;
use App\Models\FestivalOffer;
use App\Models\DiscountRule;
use App\Models\Coupon;
use Exception;

class PromotionEngine
{
    /**
     * Calculate the entire cart, applying promotions in priority order.
     */
    public function calculateCart(array $cartItems, $customer = null, $branch = null, array $availableOffers = []): array
    {
        // 1. Initialize Items State
        $items = collect($cartItems)->map(function ($item, $index) {
            return (object) [
                'index' => $index,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => (float) ($item['quantity'] ?? 1),
                'base_price' => (float) ($item['base_price'] ?? 0),
                'net_price' => (float) ($item['base_price'] ?? 0),
                
                // Promotion State
                'is_free_item' => false,
                'free_item_reason' => null,
                'exclusive_lock' => false, // Prevents further discounts if a non-stackable offer applied
                
                'applied_promotions' => [], // Array of applied promo details
                'total_discount_amount' => 0,
            ];
        });

        // Expand quantities for item-level processing (e.g. qty 2 -> 2 separate items)
        $expandedItems = $this->expandItems($items);

        // 2. Prioritize Offers
        $offers = collect($availableOffers)->sortByDesc('priority');

        // 3. Evaluate Offers
        $auditLog = [];

        foreach ($offers as $offer) {
            $type = $offer['type'];
            
            if ($type === 'B1G1') {
                $expandedItems = $this->applyBuyXGetY($expandedItems, $offer, $auditLog);
            } 
            elseif (in_array($type, ['PRODUCT_DISCOUNT', 'FESTIVAL', 'CUSTOMER_GROUP'])) {
                $expandedItems = $this->applyPercentageDiscount($expandedItems, $offer, $auditLog);
            }
        }

        // 4. Re-collapse items
        $collapsedItems = $this->collapseItems($expandedItems);
        
        return [
            'items' => $collapsedItems,
            'audit_log' => $auditLog
        ];
    }

    protected function expandItems(Collection $items): Collection
    {
        $expanded = collect();
        foreach ($items as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                // clone the item but set qty to 1
                $cloned = clone $item;
                $cloned->quantity = 1;
                $expanded->push($cloned);
            }
        }
        return $expanded;
    }

    protected function collapseItems(Collection $expandedItems): array
    {
        $collapsed = [];
        
        foreach ($expandedItems as $item) {
            $key = $item->index;
            if (!isset($collapsed[$key])) {
                $collapsed[$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => 0,
                    'base_price' => $item->base_price,
                    'net_price' => 0,
                    'total_discount_amount' => 0,
                    'is_free_item' => false,
                    'free_item_reason' => null,
                    'applied_promotions' => [],
                ];
            }

            $collapsed[$key]['quantity'] += 1;
            
            // If any one piece is free, we might need a separate line in a real POS, 
            // but for summary we aggregate
            if ($item->is_free_item) {
                $collapsed[$key]['is_free_item'] = true;
                $collapsed[$key]['free_item_reason'] = $item->free_item_reason;
            } else {
                $collapsed[$key]['net_price'] += $item->net_price;
            }
            
            $collapsed[$key]['total_discount_amount'] += $item->total_discount_amount;
            
            foreach ($item->applied_promotions as $promo) {
                // Ensure unique promos recorded
                $exists = false;
                foreach ($collapsed[$key]['applied_promotions'] as $exP) {
                    if ($exP['id'] === $promo['id'] && $exP['type'] === $promo['type']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $collapsed[$key]['applied_promotions'][] = $promo;
                }
            }
        }
        
        // Finalize net_price average if needed, or keep it as total net price for the line
        return array_values($collapsed);
    }

    protected function applyBuyXGetY(Collection $items, array $offer, array &$auditLog): Collection
    {
        $buyProductId = $offer['buy_product_id'];
        
        // Find all eligible products that are not already free
        $eligibleItems = $items->filter(function ($item) use ($buyProductId) {
            return $item->product_id == $buyProductId && !$item->is_free_item;
        });

        $buyQty = $offer['buy_quantity'];
        $getQty = $offer['get_quantity'];
        $groupSize = $buyQty + $getQty;

        $totalEligible = $eligibleItems->count();
        $groups = floor($totalEligible / $groupSize);

        if ($groups > 0) {
            $freeCount = $groups * $getQty;

            // Sort by base_price DESC (Highest paid, lowest free)
            $sortedItems = $eligibleItems->sortByDesc('base_price')->values();
            
            // The last $freeCount items become free
            $startIndexForFree = $sortedItems->count() - $freeCount;

            for ($i = $startIndexForFree; $i < $sortedItems->count(); $i++) {
                $item = $sortedItems[$i];
                
                // Best price logic: If item already has a better absolute discount, skip making it free?
                // Actually B1G1 making it free is usually the best discount (100%), so we force it.
                
                $discountAmount = $item->net_price; // 100% of current net price

                $item->is_free_item = true;
                $item->free_item_reason = 'B1G1: ' . $offer['name'];
                $item->net_price = 0;
                $item->total_discount_amount += $discountAmount;
                $item->exclusive_lock = !$offer['is_stackable'];

                $promoData = [
                    'id' => $offer['id'],
                    'type' => $offer['type'],
                    'name' => $offer['name'],
                    'discount_amount' => $discountAmount,
                ];
                $item->applied_promotions[] = $promoData;

                $auditLog[] = [
                    'promotion' => $offer['name'],
                    'product_id' => $item->product_id,
                    'status' => 'APPLIED',
                    'reason' => 'B1G1 applied. Item is free.',
                    'discount_amount' => $discountAmount
                ];
            }
        } else {
            $auditLog[] = [
                'promotion' => $offer['name'],
                'status' => 'NOT_ELIGIBLE',
                'reason' => 'Insufficient quantity for B1G1 group.',
            ];
        }

        return $items;
    }

    protected function applyPercentageDiscount(Collection $items, array $offer, array &$auditLog): Collection
    {
        $targetProductId = $offer['target_product_id'] ?? null;
        $discountPercent = $offer['percentage'];

        $eligibleItems = $items->filter(function ($item) use ($targetProductId) {
            if ($item->is_free_item) return false;
            if ($targetProductId && $item->product_id != $targetProductId) return false;
            return true;
        });

        if ($eligibleItems->isEmpty()) {
            $auditLog[] = [
                'promotion' => $offer['name'],
                'status' => 'NOT_ELIGIBLE',
                'reason' => 'No eligible non-free items found.',
            ];
            return $items;
        }

        foreach ($eligibleItems as $item) {
            if ($item->exclusive_lock) {
                $auditLog[] = [
                    'promotion' => $offer['name'],
                    'product_id' => $item->product_id,
                    'status' => 'REJECTED',
                    'reason' => 'Item is locked by a higher priority exclusive offer.',
                ];
                continue;
            }

            // Calculate potential discount
            $potentialDiscount = round($item->net_price * ($discountPercent / 100), 2);

            if ($potentialDiscount <= 0) continue;

            // Apply discount
            $item->net_price -= $potentialDiscount;
            $item->total_discount_amount += $potentialDiscount;
            
            if (!$offer['is_stackable']) {
                $item->exclusive_lock = true;
            }

            $promoData = [
                'id' => $offer['id'],
                'type' => $offer['type'],
                'name' => $offer['name'],
                'discount_amount' => $potentialDiscount,
            ];
            $item->applied_promotions[] = $promoData;

            $auditLog[] = [
                'promotion' => $offer['name'],
                'product_id' => $item->product_id,
                'status' => 'APPLIED',
                'reason' => 'Percentage discount applied.',
                'discount_amount' => $potentialDiscount
            ];
        }

        return $items;
    }
}
