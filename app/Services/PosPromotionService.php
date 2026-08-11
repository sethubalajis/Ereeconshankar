<?php

namespace App\Services;

use App\Models\BuyXGetYOffer;
use App\Models\ComboOffer;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\DiscountRule;
use App\Models\FestivalOffer;
use App\Models\LoyaltyProgram;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PromoCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class PosPromotionService
{
    /**
     * Fetch all eligible benefits and promotions for a given customer and cart items.
     *
     * @param int|null $customerId
     * @param array $cartItems Array of items with keys: product_id, product_variant_id, quantity, unit_price
     * @return array
     */
    public function getEligibleOffers(?int $customerId, array $cartItems): array
    {
        $subtotal = 0;
        $cartProductIds = [];
        $cartBrandIds = [];
        $cartCategoryIds = [];

        foreach ($cartItems as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $subtotal += ($qty * $price);

            $productId = $item['product_id'] ?? null;
            $variantId = $item['product_variant_id'] ?? null;

            if ($productId) {
                $cartProductIds[] = (int) $productId;
            } elseif ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant && $variant->product_id) {
                    $cartProductIds[] = (int) $variant->product_id;
                }
            }
        }

        $cartProductIds = array_unique(array_filter($cartProductIds));

        if (! empty($cartProductIds)) {
            $products = Product::whereIn('id', $cartProductIds)->get();
            foreach ($products as $p) {
                if ($p->brand_id) $cartBrandIds[] = (int) $p->brand_id;
                if ($p->product_category_id) $cartCategoryIds[] = (int) $p->product_category_id;
            }
        }

        $cartBrandIds = array_unique(array_filter($cartBrandIds));
        $cartCategoryIds = array_unique(array_filter($cartCategoryIds));

        $offers = [];
        $now = Carbon::now();

        $promoSettings = \App\Models\PromotionSetting::all()->keyBy('offer_type');
        $applyPromoSettings = function ($offerType, array &$offerDef) use ($promoSettings, $now, $subtotal) {
            $setting = $promoSettings->get($offerType);
            if (!$setting) {
                // Defaults if no setting configured
                $offerDef['priority'] = 0;
                $offerDef['is_stackable'] = false;
                $offerDef['is_exclusive'] = false;
                $offerDef['best_price_only'] = false;
                return true;
            }

            // Global rules check from PromotionSetting
            if ($setting->start_date && $now->lt($setting->start_date)) return false;
            if ($setting->end_date && $now->gt($setting->end_date)) return false;
            if ($setting->min_bill_amount > 0 && $subtotal < $setting->min_bill_amount) return false;
            
            // Apply settings to offer
            $offerDef['priority'] = $setting->priority;
            $offerDef['is_stackable'] = $setting->is_stackable;
            $offerDef['is_exclusive'] = $setting->is_exclusive;
            $offerDef['best_price_only'] = $setting->best_price_only;
            $offerDef['exclusions'] = $setting->exclusions ?? [];
            $offerDef['stack_group'] = $setting->stack_group;
            $offerDef['max_discount'] = $setting->max_discount;
            return true;
        };

        // 1. Customer Benefits
        if ($customerId) {
            $customer = Customer::with(['customerGroup', 'loyaltyCards'])->find($customerId);
            if ($customer) {
                // Customer Group Discount
                $group = $customer->customerGroup;
                if ($group && $group->status && $group->discount_percentage > 0) {
                    $discVal = round(($subtotal * $group->discount_percentage) / 100, 2);
                    $offerArray = [
                        'key' => 'customer_group',
                        'category' => 'Customer Group',
                        'title' => "Customer Group ({$group->name}): {$group->discount_percentage}% off",
                        'type' => 'percentage',
                        'value' => (float) $group->discount_percentage,
                        'estimated_discount' => $discVal,
                    ];
                    if ($applyPromoSettings('CUSTOMER_GROUP', $offerArray)) {
                        $offers['customer_group'] = $offerArray;
                    }
                }

                // Membership Plan Discount
                $activeMembershipAssignment = $customer->activeMembership()->with('membershipPlan')->first();
                $plan = $activeMembershipAssignment ? $activeMembershipAssignment->membershipPlan : null;

                if ($plan && $plan->status && isset($plan->discount_percentage) && $plan->discount_percentage > 0) {
                    $discVal = round(($subtotal * $plan->discount_percentage) / 100, 2);
                    $offerArray = [
                        'key' => 'membership_plan',
                        'category' => 'Membership Plan',
                        'title' => "Membership ({$plan->name}): {$plan->discount_percentage}% off",
                        'type' => 'percentage',
                        'value' => (float) $plan->discount_percentage,
                        'estimated_discount' => $discVal,
                    ];
                    if ($applyPromoSettings('MEMBERSHIP', $offerArray)) {
                        $offers['membership_plan'] = $offerArray;
                    }
                }



            }
        }

        // 2. Coupons
        $coupons = Coupon::query()
            ->where('status', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now))
            ->where(fn($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now))
            ->where('min_purchase_amount', '<=', $subtotal)
            ->get();

        foreach ($coupons as $coupon) {
            if ($coupon->max_usage > 0 && $coupon->current_usage >= $coupon->max_usage) {
                continue;
            }
            $discVal = 0;
            if ($coupon->discount_percentage) {
                $discVal = round(($subtotal * $coupon->discount_percentage) / 100, 2);
            } elseif ($coupon->discount_amount) {
                $discVal = min((float) $coupon->discount_amount, $subtotal);
            }

            $offerArray = [
                'key' => "coupon_{$coupon->id}",
                'category' => 'Coupon',
                'title' => "Coupon ({$coupon->code}): " . ($coupon->discount_percentage ? "{$coupon->discount_percentage}% off" : "₹{$coupon->discount_amount} off"),
                'type' => $coupon->discount_percentage ? 'percentage' : 'fixed',
                'value' => (float) ($coupon->discount_percentage ?: $coupon->discount_amount),
                'estimated_discount' => $discVal,
            ];
            if ($applyPromoSettings('COUPON', $offerArray)) {
                $offers["coupon_{$coupon->id}"] = $offerArray;
            }
        }

        // 3. Promo Codes
        $promoCodes = PromoCode::query()
            ->where('status', true)
            ->where(fn($q) => $q->whereNull('valid_from')->orWhereDate('valid_from', '<=', $now))
            ->where(fn($q) => $q->whereNull('valid_to')->orWhereDate('valid_to', '>=', $now))
            ->get();

        foreach ($promoCodes as $promo) {
            $discVal = 0;
            if ($promo->discount_percentage) {
                $discVal = round(($subtotal * $promo->discount_percentage) / 100, 2);
            } elseif ($promo->discount_amount) {
                $discVal = min((float) $promo->discount_amount, $subtotal);
            }

            $offerArray = [
                'key' => "promo_code_{$promo->id}",
                'category' => 'Promo Code',
                'title' => "Promo Code ({$promo->code}): " . ($promo->discount_percentage ? "{$promo->discount_percentage}% off" : "₹{$promo->discount_amount} off"),
                'type' => $promo->discount_percentage ? 'percentage' : 'fixed',
                'value' => (float) ($promo->discount_percentage ?: $promo->discount_amount),
                'estimated_discount' => $discVal,
            ];
            if ($applyPromoSettings('PROMO_CODE', $offerArray)) {
                $offers["promo_code_{$promo->id}"] = $offerArray;
            }
        }

        // 4. Festival Offers
        $festivalOffers = FestivalOffer::query()
            ->where('status', true)
            ->whereDate('start_date', '<=', $now)
            ->whereDate('end_date', '>=', $now)
            ->get();

        $hasFestivalProd = Schema::hasColumn('festival_offers', 'product_id');
        $hasFestivalBrand = Schema::hasColumn('festival_offers', 'brand_id');
        $hasFestivalCat = Schema::hasColumn('festival_offers', 'product_category_id');

        foreach ($festivalOffers as $offer) {
            $isMatch = true;
            if ($hasFestivalProd && isset($offer->product_id) && $offer->product_id && ! in_array((int) $offer->product_id, $cartProductIds)) {
                $isMatch = false;
            }
            if ($hasFestivalBrand && isset($offer->brand_id) && $offer->brand_id && ! in_array((int) $offer->brand_id, $cartBrandIds)) {
                $isMatch = false;
            }
            if ($hasFestivalCat && isset($offer->product_category_id) && $offer->product_category_id && ! in_array((int) $offer->product_category_id, $cartCategoryIds)) {
                $isMatch = false;
            }

            if (! $isMatch) continue;

            $discVal = 0;
            if ($offer->discount_percentage) {
                $discVal = round(($subtotal * $offer->discount_percentage) / 100, 2);
            } elseif (isset($offer->discount_amount) && $offer->discount_amount) {
                $discVal = min((float) $offer->discount_amount, $subtotal);
            }

            $offerArray = [
                'key' => "festival_offer_{$offer->id}",
                'category' => 'Festival Offer',
                'title' => "Festival Offer ({$offer->name}): " . ($offer->discount_percentage ? "{$offer->discount_percentage}% off" : "₹" . ($offer->discount_amount ?? 0) . " off"),
                'type' => $offer->discount_percentage ? 'percentage' : 'fixed',
                'value' => (float) ($offer->discount_percentage ?: ($offer->discount_amount ?? 0)),
                'estimated_discount' => $discVal,
            ];
            if ($applyPromoSettings('FESTIVAL', $offerArray)) {
                $offers["festival_offer_{$offer->id}"] = $offerArray;
            }
        }

        // 5. Buy X Get Y Offers
        $buyXGetYOffers = BuyXGetYOffer::with(['buyProduct', 'getProduct'])
            ->where('status', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now))
            ->where(fn($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now))
            ->get();

        foreach ($buyXGetYOffers as $bxy) {
            $matchedQty = 0;
            foreach ($cartItems as $cItem) {
                $pId = $cItem['product_id'] ?? null;
                if (! $pId && ! empty($cItem['product_variant_id'])) {
                    $v = ProductVariant::find($cItem['product_variant_id']);
                    $pId = $v?->product_id;
                }
                if ((int) $pId === (int) $bxy->buy_product_id) {
                    $matchedQty += (float) ($cItem['quantity'] ?? 1);
                }
            }

            if ($matchedQty >= $bxy->buy_quantity) {
                $getProduct = $bxy->getProduct;
                $getProductName = $getProduct ? $getProduct->name : "Product #{$bxy->get_product_id}";
                $buyProductName = $bxy->buyProduct ? $bxy->buyProduct->name : "Product #{$bxy->buy_product_id}";

                $estimatedDisc = 0;
                if ($getProduct) {
                    $getVariant = ProductVariant::where('product_id', $getProduct->id)->first();
                    $unitPrice = $getVariant ? (float) $getVariant->selling_price : 100.0;
                    $timesTriggered = (int) floor($matchedQty / $bxy->buy_quantity);
                    $estimatedDisc = round($timesTriggered * $bxy->get_quantity * $unitPrice, 2);
                }

                $offerArray = [
                    'key' => "buy_x_get_y_{$bxy->id}",
                    'category' => 'Buy X Get Y Offer',
                    'title' => "Buy X Get Y: Buy {$bxy->buy_quantity} of {$buyProductName}, Get {$bxy->get_quantity} of {$getProductName} FREE",
                    'type' => 'free_item',
                    'estimated_discount' => $estimatedDisc,
                    // also add standard type string for engine to match:
                    'engine_type' => 'B1G1',
                    'name' => "Buy X Get Y ({$bxy->name})",
                    'buy_product_id' => $bxy->buy_product_id,
                    'buy_quantity' => $bxy->buy_quantity,
                    'get_quantity' => $bxy->get_quantity,
                    'id' => "BXY_{$bxy->id}"
                ];
                if ($applyPromoSettings('B1G1', $offerArray)) {
                    $offers["buy_x_get_y_{$bxy->id}"] = $offerArray;
                }
            }
        }

        // 6. Combo Offers
        $comboOffers = ComboOffer::query()
            ->where('status', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now))
            ->where(fn($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now))
            ->get();

        foreach ($comboOffers as $combo) {
            $discVal = 0;
            if (isset($combo->discount_percentage) && $combo->discount_percentage) {
                $discVal = round(($subtotal * $combo->discount_percentage) / 100, 2);
            } elseif (isset($combo->discount_amount) && $combo->discount_amount) {
                $discVal = min((float) $combo->discount_amount, $subtotal);
            } else {
                $discVal = max(0, round($subtotal - (float) $combo->total_price, 2));
            }

            if ($discVal > 0) {
                $offerArray = [
                    'key' => "combo_offer_{$combo->id}",
                    'category' => 'Combo Offer',
                    'title' => "Combo Offer ({$combo->name}): Combo Special Price",
                    'type' => 'combo',
                    'estimated_discount' => $discVal,
                ];
                if ($applyPromoSettings('COMBO', $offerArray)) {
                    $offers["combo_offer_{$combo->id}"] = $offerArray;
                }
            }
        }

        // 7. Discount Rules & Price Lists
        $discountRules = DiscountRule::query()
            ->where('status', true)
            ->where(fn($q) => $q->whereNull('start_date')->orWhereDate('start_date', '<=', $now))
            ->where(fn($q) => $q->whereNull('end_date')->orWhereDate('end_date', '>=', $now))
            ->where('min_purchase_amount', '<=', $subtotal)
            ->get();

        $hasRuleProd = Schema::hasColumn('discount_rules', 'product_id');
        $hasRuleBrand = Schema::hasColumn('discount_rules', 'brand_id');
        $hasRuleCat = Schema::hasColumn('discount_rules', 'product_category_id');

        foreach ($discountRules as $rule) {
            $isMatch = true;
            if ($hasRuleProd && isset($rule->product_id) && $rule->product_id && ! in_array((int) $rule->product_id, $cartProductIds)) {
                $isMatch = false;
            }
            if ($hasRuleBrand && isset($rule->brand_id) && $rule->brand_id && ! in_array((int) $rule->brand_id, $cartBrandIds)) {
                $isMatch = false;
            }
            if ($hasRuleCat && isset($rule->product_category_id) && $rule->product_category_id && ! in_array((int) $rule->product_category_id, $cartCategoryIds)) {
                $isMatch = false;
            }

            if (! $isMatch) continue;

            $discVal = 0;
            if ($rule->discount_percentage) {
                $discVal = round(($subtotal * $rule->discount_percentage) / 100, 2);
            } elseif ($rule->discount_amount) {
                $discVal = min((float) $rule->discount_amount, $subtotal);
            }

            $offerArray = [
                'key' => "discount_rule_{$rule->id}",
                'category' => 'Discount Rule',
                'title' => "Discount Rule ({$rule->code} - {$rule->name}): " . ($rule->discount_percentage ? "{$rule->discount_percentage}% off" : "₹{$rule->discount_amount} off"),
                'type' => $rule->discount_percentage ? 'percentage' : 'fixed',
                'value' => (float) ($rule->discount_percentage ?: $rule->discount_amount),
                'estimated_discount' => $discVal,
            ];
            if ($applyPromoSettings('DISCOUNT_RULE', $offerArray)) {
                $offers["discount_rule_{$rule->id}"] = $offerArray;
            }
        }



        return $offers;
    }

    /**
     * Compute total discounts from selected offer keys.
     *
     * @param array $selectedKeys
     * @param array $eligibleOffers Output of getEligibleOffers()
     * @param float $subtotal
     * @return array Array containing promo_discount, points_redeem_amount, points_to_redeem, wallet_amount, applied_details
     */
    public function calculateSelectedOffers(array $selectedKeys, array $eligibleOffers, float $subtotal): array
    {
        $promoDiscount = 0;
        $pointsRedeemAmount = 0;
        $pointsToRedeem = 0;
        $walletAmount = 0;
        $appliedDetails = [];
        $promotionCount = 0;

        foreach ($selectedKeys as $key) {
            if (! isset($eligibleOffers[$key])) continue;
            
            if (!in_array($key, ['loyalty_points', 'wallet_balance'])) {
                $promotionCount++;
            }
        }

        if ($promotionCount > 1) {
            throw new \Exception('Multiple promotional offers cannot be combined manually. Please select only the single best offer or a valid stackable combination.');
        }

        foreach ($selectedKeys as $key) {
            if (! isset($eligibleOffers[$key])) {
                continue;
            }

            $offer = $eligibleOffers[$key];

            if ($key === 'wallet_balance') {
                $walletAmount = $offer['estimated_discount'] ?? 0;
                $appliedDetails[] = [
                    'key' => $key,
                    'title' => $offer['title'],
                    'amount' => $walletAmount,
                    'type' => 'wallet',
                ];
            } else {
                $disc = $offer['estimated_discount'] ?? 0;
                $promoDiscount += $disc;
                $appliedDetails[] = [
                    'key' => $key,
                    'title' => $offer['title'],
                    'amount' => $disc,
                    'type' => $offer['type'] ?? 'promotion',
                ];
            }
        }

        return [
            'promo_discount' => round($promoDiscount, 2),
            'points_redeem_amount' => round($pointsRedeemAmount, 2),
            'points_to_redeem' => $pointsToRedeem,
            'wallet_amount' => round($walletAmount, 2),
            'applied_details' => $appliedDetails,
        ];
    }
}
