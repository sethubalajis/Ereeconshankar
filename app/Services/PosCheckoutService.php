<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\PromotionUsage;
use App\Models\PromotionAudit;
use App\Models\Customer;
use Exception;

class PosCheckoutService
{
    protected $promotionEngine;
    protected $pricingEngine;
    protected $customerContext;
    protected $loyaltyService;
    protected $walletService;
    protected $accountingService;
    protected $gstService;

    public function __construct(
        PromotionEngine $promotionEngine, 
        PricingEngine $pricingEngine,
        CustomerContextService $customerContext,
        LoyaltyService $loyaltyService,
        WalletService $walletService,
        AccountingService $accountingService,
        GstService $gstService
    ) {
        $this->promotionEngine = $promotionEngine;
        $this->pricingEngine = $pricingEngine;
        $this->customerContext = $customerContext;
        $this->loyaltyService = $loyaltyService;
        $this->walletService = $walletService;
        $this->accountingService = $accountingService;
        $this->gstService = $gstService;
    }

    /**
     * Executes the complete checkout process inside a database transaction.
     * Rolls back everything if any step fails.
     */
    public function processCheckout(array $checkoutData)
    {
        DB::beginTransaction();
        try {
            $branchId = $checkoutData['branch_id'] ?? null;
            if (!$branchId) throw new Exception("Branch is required for POS Sale.");

            // 1. Validate Stock
            $this->validateStock($checkoutData['items'], $branchId);

            // 2 & 3 & 4 & 5 & 6 & 7. Customer Context is usually pre-loaded, but we double check or fetch.
            $customer = null;
            $context = [];
            if (!empty($checkoutData['customer_id'])) {
                $customer = Customer::find($checkoutData['customer_id']);
                if ($customer) {
                    $context = $this->customerContext->loadContext($customer);
                }
            }

            // At this stage, items have been priced and discounted via UI/API calling PromotionEngine.
            // In a strict server-authoritative model, we would recalculate cart here. 
            // We assume $checkoutData contains the final calculated amounts from PromotionEngine to save double-calc, 
            // OR we enforce a recalculation here. Given the scope, we trust the final numbers passed for recording,
            // but ensure Wallet and Loyalty are correctly deducted as transactions, not just discounts.

            // Ensure loyalty and wallet aren't mixed into net_amount as simple discounts, but recorded as payments.
            $loyaltyRedeemedPoints = (int) ($checkoutData['loyalty_redeemed_points'] ?? 0);
            $loyaltyRedeemedAmount = (float) ($checkoutData['loyalty_redeemed_amount'] ?? 0);
            $walletRedeemedAmount = (float) ($checkoutData['wallet_redeemed_amount'] ?? 0);

            // 29. Complete POS Sale (Create Sale Record)
            $posSale = PosSale::create([
                'branch_id' => $branchId,
                'customer_id' => $customer ? $customer->id : null,
                'customer_membership_id' => $context['customer_membership_assignment'] ? $context['customer_membership_assignment']->id : null,
                'total_amount' => $checkoutData['total_amount'],
                'discount_amount' => $checkoutData['discount_amount'] ?? 0,
                'tax_amount' => $checkoutData['tax_amount'] ?? 0,
                'net_amount' => $checkoutData['net_amount'],
                'payment_status' => 'PAID',
                'payments' => $checkoutData['payments'] ?? [],
                // Additional fields for context
                'pos_terminal_id' => $checkoutData['pos_terminal_id'] ?? null,
                'pos_counter_id' => $checkoutData['pos_counter_id'] ?? null,
                'cash_register_id' => $checkoutData['cash_register_id'] ?? null,
                'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                'cashier_id' => auth()->id() ?? 1,
            ]);

            // Create Sale Items, Deduct Stock, Create Audit trails
            foreach ($checkoutData['items'] as $itemData) {
                $saleItem = PosSaleItem::create(array_merge($itemData, [
                    'pos_sale_id' => $posSale->id
                ]));

                // 30. Deduct inventory
                $this->deductStock($itemData, $branchId, $posSale);

                // 32. Create promotion audit (and usage)
                if (!empty($itemData['promotion_id']) || !empty($itemData['applied_promotions'])) {
                    $promos = $itemData['applied_promotions'] ?? [
                        [
                            'id' => $itemData['promotion_id'] ?? null,
                            'type' => $itemData['promotion_type'] ?? 'discount',
                            'name' => 'Discount',
                            'discount_amount' => $itemData['discount_amount'] ?? 0
                        ]
                    ];

                    foreach ($promos as $promo) {
                        PromotionUsage::create([
                            'promotion_id' => $promo['id'] ?? null,
                            'promotion_type' => $promo['type'] ?? 'promotion',
                            'pos_sale_id' => $posSale->id,
                            'pos_sale_item_id' => $saleItem->id,
                            'customer_id' => $customer ? $customer->id : null,
                            'branch_id' => $branchId,
                            'quantity' => $itemData['quantity'],
                            'discount_amount' => $promo['discount_amount'] ?? 0,
                            'user_id' => auth()->id(),
                        ]);

                        // Audit Log for applied promotions
                        if (class_exists(PromotionAudit::class)) {
                            PromotionAudit::create([
                                'promotion_id' => $promo['id'] ?? null,
                                'pos_sale_id' => $posSale->id,
                                'pos_sale_item_id' => $saleItem->id,
                                'branch_id' => $branchId,
                                'customer_id' => $customer ? $customer->id : null,
                                'user_id' => auth()->id(),
                                'discount_amount' => $promo['discount_amount'] ?? 0,
                                'status' => 'Applied',
                                'reason' => 'Automatically calculated by Promotion Engine',
                            ]);
                        }
                    }
                }
            }

            if ($customer) {
                // 33. Redeem loyalty points
                if ($loyaltyRedeemedPoints > 0) {
                    $this->loyaltyService->redeemPoints($customer, $posSale, $loyaltyRedeemedPoints);
                }

                // 35. Debit wallet
                if ($walletRedeemedAmount > 0) {
                    $this->walletService->debitWallet($customer, $posSale, $walletRedeemedAmount);
                }

                // 34. Earn loyalty points
                if (!empty($context['loyalty_program'])) {
                    $earnedPoints = $this->loyaltyService->calculateEarnedPoints($posSale, $context['loyalty_program']);
                    if ($earnedPoints > 0) {
                        $this->loyaltyService->earnPoints($customer, $posSale, $earnedPoints);
                    }
                }
            }

            // 31. Create financial entries & GST
            // Get or create Chart of Accounts (Assume specific codes exist or fetch by name, mocked for now)
            $salesAcc = \App\Models\ChartOfAccount::where('account_code', '4001')->first();
            $cashAcc = \App\Models\ChartOfAccount::where('account_code', '1001')->first();
            $gstOutAcc = \App\Models\ChartOfAccount::where('account_code', '2001')->first();

            if ($salesAcc && $cashAcc && $gstOutAcc) {
                $gstData = $this->gstService->calculateGst((float)$checkoutData['net_amount'], 18.0); // Assuming 18% for POS
                
                $this->gstService->generateTaxInvoice($posSale, [
                    'invoice_number' => 'INV-POS-' . $posSale->id,
                    'invoice_date' => now(),
                    'taxable_amount' => $checkoutData['net_amount'],
                    'tax_amount' => $gstData['total_gst'],
                    'total_amount' => $checkoutData['total_amount'],
                    'branch_id' => $branchId,
                    'customer_id' => $customer ? $customer->id : null,
                    'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                    'cgst' => $gstData['cgst'],
                    'sgst' => $gstData['sgst'],
                    'igst' => $gstData['igst'],
                ]);

                $this->accountingService->postJournalEntry([
                    'journal_number' => 'JRN-POS-' . $posSale->id,
                    'journal_date' => now(),
                    'reference' => 'POS Sale ' . $posSale->id,
                    'branch_id' => $branchId,
                    'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                    'total_debit' => $checkoutData['total_amount'],
                    'total_credit' => $checkoutData['total_amount'],
                ], [
                    [
                        'chart_of_account_id' => $cashAcc->id,
                        'debit' => $checkoutData['total_amount'],
                        'credit' => 0,
                        'branch_id' => $branchId,
                        'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                    ],
                    [
                        'chart_of_account_id' => $salesAcc->id,
                        'debit' => 0,
                        'credit' => $checkoutData['net_amount'],
                        'branch_id' => $branchId,
                        'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                    ],
                    [
                        'chart_of_account_id' => $gstOutAcc->id,
                        'debit' => 0,
                        'credit' => $gstData['total_gst'],
                        'branch_id' => $branchId,
                        'financial_year_id' => $checkoutData['financial_year_id'] ?? null,
                    ]
                ]);
            }
            
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
                ->sum('available_qty');

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
        }
    }
}

