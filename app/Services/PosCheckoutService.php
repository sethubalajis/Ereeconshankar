<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\StockLedger;
use App\Models\PromotionUsage;
use App\Models\LoyaltyTransaction;
use App\Models\GiftVoucherTransaction;
use Exception;

class PosCheckoutService
{
    protected $promotionEngine;
    protected $pricingEngine;

    public function __construct(PromotionEngine $promotionEngine, PricingEngine $pricingEngine)
    {
        $this->promotionEngine = $promotionEngine;
        $this->pricingEngine = $pricingEngine;
    }

    /**
     * Executes the complete checkout process inside a database transaction.
     * Rolls back everything if any step fails.
     */
    public function processCheckout(array $checkoutData)
    {
        DB::beginTransaction();
        try {
            // 1. Validate Stock
            $this->validateStock($checkoutData['items'], $checkoutData['branch_id']);

            // 2. Validate Promotions & Recalculate (Server-side verification)
            // (Assuming $checkoutData['items'] contains verified prices from the controller for this example)

            // 3. Create POS Sale
            $posSale = PosSale::create([
                'branch_id' => $checkoutData['branch_id'],
                'customer_id' => $checkoutData['customer_id'] ?? null,
                'total_amount' => $checkoutData['total_amount'],
                'discount_amount' => $checkoutData['discount_amount'] ?? 0,
                'tax_amount' => $checkoutData['tax_amount'] ?? 0,
                'net_amount' => $checkoutData['net_amount'],
                'payment_status' => 'PAID',
                'payments' => $checkoutData['payments'] ?? [],
                // other necessary fields...
            ]);

            // 4. Create Sale Items, Deduct Stock, Create Audit trails
            foreach ($checkoutData['items'] as $itemData) {
                $saleItem = PosSaleItem::create(array_merge($itemData, [
                    'pos_sale_id' => $posSale->id
                ]));

                // Deduct stock for ALL items (including free items)
                $this->deductStock($itemData, $checkoutData['branch_id'], $posSale);

                // Log Promotion Usage
                if (!empty($itemData['promotion_id'])) {
                    PromotionUsage::create([
                        'promotion_id' => $itemData['promotion_id'],
                        'promotion_type' => $itemData['promotion_type'],
                        'pos_sale_id' => $posSale->id,
                        'pos_sale_item_id' => $saleItem->id,
                        'customer_id' => $checkoutData['customer_id'] ?? null,
                        'branch_id' => $checkoutData['branch_id'],
                        'quantity' => $itemData['quantity'],
                        'discount_amount' => $itemData['discount_amount'],
                        'user_id' => auth()->id(),
                    ]);
                }
            }

            // 5. Loyalty Points Integration
            if (!empty($checkoutData['loyalty_redeemed'])) {
                // Deduct points, create LoyaltyTransaction
            }
            // Add earned loyalty points based on net_amount

            // 6. Gift Voucher Integration
            if (!empty($checkoutData['gift_voucher'])) {
                // Deduct balance, create GiftVoucherTransaction
            }

            // 7. Finance / Accounting Integration
            // Create Journal Entries for Sales, GST, Discount, Cash/Bank

            DB::commit();
            return $posSale;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function validateStock(array $items, $branchId)
    {
        foreach ($items as $item) {
            $stock = Stock::where('branch_id', $branchId)
                ->where('product_id', $item['product_id'])
                ->where('product_variant_id', $item['product_variant_id'] ?? null)
                ->sum('quantity');

            if ($stock < $item['quantity']) {
                throw new Exception("Insufficient stock for product ID: {$item['product_id']}");
            }
        }
    }

    protected function deductStock(array $item, $branchId, PosSale $posSale)
    {
        $stockRecord = Stock::where('branch_id', $branchId)
            ->where('product_id', $item['product_id'])
            ->where('product_variant_id', $item['product_variant_id'] ?? null)
            ->first();

        if ($stockRecord) {
            $stockRecord->decrement('quantity', $item['quantity']);

            // Create Stock Transaction
            StockTransaction::create([
                'stock_id' => $stockRecord->id,
                'type' => 'OUT',
                'quantity' => $item['quantity'],
                'reference_type' => 'POS_SALE',
                'reference_id' => $posSale->id,
                'branch_id' => $branchId,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'] ?? null,
            ]);

            // Create Stock Ledger (omitted for brevity)
        }
    }
}
