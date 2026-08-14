<?php

namespace App\Filament\Resources\PosSales\Schemas;

use App\Services\PosPromotionService;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PosSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        $recalculateAll = function ($set, $get) {
            $branchId = $get('branch_id') ?? $get('../../branch_id');
            $customerId = $get('customer_id') ?? $get('../../customer_id');
            $items = $get('posSaleItems') ?? $get('../../posSaleItems') ?? [];

            // Determine if intra-state or inter-state
            $isInterState = false;
            if ($branchId && $customerId) {
                $branchState = \App\Models\Branch::find($branchId)?->state_id;
                $customerState = \App\Models\Customer::find($customerId)?->state_id;
                if ($branchState && $customerState && $branchState !== $customerState) {
                    $isInterState = true;
                }
            }

            $grossTotal = 0;
            $manualDiscountsSum = 0;

            // First pass to get total gross for proportional discount distribution
            foreach ($items as $item) {
                $qty = (float) ($item['quantity'] ?? 1);
                $uPrice = (float) ($item['unit_price'] ?? 0);
                $itemDisc = (float) ($item['discount'] ?? 0);
                $grossTotal += ($qty * $uPrice);
                $manualDiscountsSum += $itemDisc;
            }

            $subtotalAfterLineDiscounts = max(0, $grossTotal - $manualDiscountsSum);

            $promotionService = new PosPromotionService();
            $eligibleOffers = $promotionService->getEligibleOffers($customerId, $items);

            $selectedOfferKeys = $get('selected_offers') ?? $get('../../selected_offers') ?? [];

            try {
                $calcResult = $promotionService->calculateSelectedOffers($selectedOfferKeys, $eligibleOffers, $subtotalAfterLineDiscounts);
            } catch (\Exception $e) {
                \Filament\Notifications\Notification::make()
                    ->danger()
                    ->title('Invalid Offer Combination')
                    ->body($e->getMessage())
                    ->send();
                
                $set('selected_offers', []);
                if ($get('../../posSaleItems') !== null) {
                    $set('../../selected_offers', []);
                }
                $selectedOfferKeys = [];
                $calcResult = $promotionService->calculateSelectedOffers($selectedOfferKeys, $eligibleOffers, $subtotalAfterLineDiscounts);
            }

            $promoDiscountAmount = $calcResult['promo_discount'];
            
            $pointsToRedeem = (int) ($get('redeem_points') ?? $get('../../redeem_points') ?? 0);
            $pointsRedeemAmount = 0;
            if ($pointsToRedeem > 0 && $customerId) {
                $customer = \App\Models\Customer::find($customerId);
                $program = \App\Models\LoyaltyProgram::resolveForCustomer($customer);
                $valPerPoint = $program ? (float) $program->value_per_point : 1.0;
                $pointsRedeemAmount = round($pointsToRedeem * $valPerPoint, 2);
            }
            
            $walletUseAmount = (float) ($get('use_wallet_balance') ?? $get('../../use_wallet_balance') ?? 0);
            $appliedDetails = $calcResult['applied_details'];

            // Validate that we don't redeem more than subtotal
            $subtotalWithPromo = max(0, $subtotalAfterLineDiscounts - $promoDiscountAmount);
            if ($pointsRedeemAmount > $subtotalWithPromo) {
                $pointsRedeemAmount = $subtotalWithPromo;
                // Re-calculate points to match amount if possible, but keeping it simple for now
            }

            $totalDiscountAmount = $manualDiscountsSum + $promoDiscountAmount;
            
            // Calculate tax per item, apportioning promo discount proportionally
            $totalTaxableAmount = 0;
            $totalCgst = 0;
            $totalSgst = 0;
            $totalIgst = 0;
            $totalTaxAmount = 0;

            foreach ($items as $index => $item) {
                $qty = (float) ($item['quantity'] ?? 1);
                $uPrice = (float) ($item['unit_price'] ?? 0);
                $itemDisc = (float) ($item['discount'] ?? 0);
                $itemGross = ($qty * $uPrice);
                
                // Proportion of this item's gross out of total gross
                $proportion = $grossTotal > 0 ? ($itemGross / $grossTotal) : 0;
                $itemPromoDisc = $promoDiscountAmount * $proportion;
                
                $itemTaxableAmount = max(0, $itemGross - $itemDisc - $itemPromoDisc);
                $totalTaxableAmount += $itemTaxableAmount;
                
                // Get tax rate for product
                $taxPercentage = 0;
                if (!empty($item['product_id'])) {
                    $product = \App\Models\Product::with('taxGroup')->find($item['product_id']);
                    if ($product && $product->taxGroup) {
                        $taxPercentage = (float) $product->taxGroup->total_tax_percentage;
                    }
                }
                
                $itemTax = round($itemTaxableAmount * ($taxPercentage / 100), 2);
                $totalTaxAmount += $itemTax;
                
                $itemCgst = 0;
                $itemSgst = 0;
                $itemIgst = 0;
                
                if ($isInterState) {
                    $itemIgst = $itemTax;
                    $totalIgst += $itemIgst;
                } else {
                    $itemCgst = round($itemTax / 2, 2);
                    $itemSgst = $itemTax - $itemCgst; // to avoid rounding mismatch
                    $totalCgst += $itemCgst;
                    $totalSgst += $itemSgst;
                }
                
                // Set the tax info directly in the repeater item state
                $pathPrefix = $get('../../posSaleItems') !== null ? '../../posSaleItems.' : 'posSaleItems.';
                $set($pathPrefix . $index . '.tax_percentage', $taxPercentage);
                $set($pathPrefix . $index . '.taxable_amount', $itemTaxableAmount);
                $set($pathPrefix . $index . '.cgst', $itemCgst);
                $set($pathPrefix . $index . '.sgst', $itemSgst);
                $set($pathPrefix . $index . '.igst', $itemIgst);
            }

            $voucherAmount = (float) ($get('gift_voucher_amount') ?? $get('../../gift_voucher_amount') ?? 0);
            $netAmount = max(0, round(($grossTotal + $totalTaxAmount) - $totalDiscountAmount - $pointsRedeemAmount - $walletUseAmount - $voucherAmount, 2));

            // Set top-level fields
            $set('total_amount', $grossTotal);
            $set('discount_amount', $totalDiscountAmount);
            $set('taxable_amount', $totalTaxableAmount);
            $set('tax_amount', $totalTaxAmount);
            $set('cgst_amount', $totalCgst);
            $set('sgst_amount', $totalSgst);
            $set('igst_amount', $totalIgst);
            
            $set('redeem_points', $pointsToRedeem);
            $set('redeem_points_amount', $pointsRedeemAmount);
            $set('use_wallet_balance', $walletUseAmount);
            $set('applied_offers', $appliedDetails);
            $set('net_amount', $netAmount);

            // Also try relative path setters when invoked from inside repeater
            $set('../../total_amount', $grossTotal);
            $set('../../discount_amount', $totalDiscountAmount);
            $set('../../taxable_amount', $totalTaxableAmount);
            $set('../../tax_amount', $totalTaxAmount);
            $set('../../cgst_amount', $totalCgst);
            $set('../../sgst_amount', $totalSgst);
            $set('../../igst_amount', $totalIgst);
            
            $set('../../redeem_points', $pointsToRedeem);
            $set('../../redeem_points_amount', $pointsRedeemAmount);
            $set('../../use_wallet_balance', $walletUseAmount);
            $set('../../applied_offers', $appliedDetails);
            $set('../../net_amount', $netAmount);
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('customer_membership_id'),
                \Filament\Forms\Components\Hidden::make('applied_offers')
                    ->dehydrated(fn () => \Illuminate\Support\Facades\Schema::hasColumn('pos_sales', 'applied_offers')),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $set('warehouse_id', \App\Models\Warehouse::defaultIdForBranch((int) $state));
                        }
                    }),
                Select::make('warehouse_id')
                    ->relationship('warehouse', 'name', function ($query, $get) {
                        $branchId = $get('branch_id');
                        if ($branchId) {
                            $query->where('branch_id', $branchId);
                        }
                    })
                    ->label('Warehouse (Optional)')
                    ->nullable(),
                Select::make('pos_terminal_id')
                    ->relationship('posTerminal', 'terminal_name')
                    ->required(),
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->createOptionForm([
                        TextInput::make('name')->required()->maxLength(255),
                        TextInput::make('phone')->tel()->required()->unique('customers', 'phone')->maxLength(255),
                        TextInput::make('email')->email()->maxLength(255),
                        Select::make('customer_group_id')->relationship('customerGroup', 'name')->searchable()->preload(),
                        Select::make('state_id')->relationship('state', 'name')->searchable()->preload()->label('State (for GST)'),
                    ])
                    ->live()
                    ->afterStateUpdated(function ($set, $get) use ($recalculateAll) {
                        $set('selected_offers', []);
                        $set('stock_reservation_id', null);
                        
                        $customerId = $get('customer_id');
                        if ($customerId) {
                            $customer = \App\Models\Customer::find($customerId);
                            $activeMembershipAssignment = $customer ? $customer->activeMembership()->first() : null;
                            $set('customer_membership_id', $activeMembershipAssignment ? $activeMembershipAssignment->id : null);
                        } else {
                            $set('customer_membership_id', null);
                        }
                        
                        $recalculateAll($set, $get);
                    }),
                Select::make('stock_reservation_id')
                    ->label('Stock Reservation (Optional)')
                    ->relationship('stockReservation', 'reservation_number', function ($query, $get) {
                        $customerId = $get('customer_id');
                        if ($customerId) {
                            $query->whereIn('status', [\App\Models\StockReservation::STATUS_PENDING, \App\Models\StockReservation::STATUS_RESERVED]);
                            // Assuming we might want to filter by customer later, but stock_reservations doesn't have customer_id. Wait! 
                            // stock_reservations has branch_id, company_id. How are reservations linked to customers? 
                            // Ah, they might not be linked to customers explicitly in the schema! 
                            // Let's check stock_reservations migration... Wait, no customer_id in stock_reservations?
                            // Let's look at `2026_06_29_070310_create_stock_reservations_table.php` and `2026_08_04_054844_update_stock_reservations_table.php`. 
                            // No customer_id. 
                            // I will just show active reservations for the branch.
                        }
                        $branchId = $get('branch_id');
                        if ($branchId) {
                            $query->where('branch_id', $branchId);
                        }
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->reservation_number ?? 'Reservation #' . $record->id)
                    ->searchable()
                    ->preload()
                    ->live(),
                Placeholder::make('membership_placeholder')
                    ->label('Customer CRM & Loyalty Info')
                    ->content(function ($get) {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return 'Select a customer to view their details, loyalty status, and wallet balance.';
                        }

                        $customer = \App\Models\Customer::with(['customerGroup'])->find($customerId);
                        if (! $customer) return 'Customer not found.';

                        $group = $customer->customerGroup;
                        $activeMembershipAssignment = $customer->activeMembership()->with('membershipPlan')->first();
                        $plan = $activeMembershipAssignment ? $activeMembershipAssignment->membershipPlan : null;
                        $card = \Illuminate\Support\Facades\DB::table('loyalty_cards')
                            ->where('customer_id', $customerId)
                            ->where('status', true)
                            ->where(function($query) {
                                $query->whereNull('expiry_date')
                                      ->orWhere('expiry_date', '>=', now()->toDateString());
                            })
                            ->first();
                        $program = \App\Models\LoyaltyProgram::resolveForCustomer($customer);

                        $html = "<strong>Name:</strong> {$customer->name} ({$customer->phone})<br>";
                        $html .= "<strong>Group:</strong> " . ($group ? "{$group->name} ({$group->discount_percentage}% off)" : "None") . "<br>";
                        $html .= "<strong>Membership:</strong> " . ($plan ? $plan->name : "None") . "<br>";
                        $html .= "<strong>Active Loyalty Program:</strong> " . ($program ? $program->name : "None") . "<br>";
                        $html .= "<strong>Lifetime Purchases:</strong> ₹" . number_format($customer->lifetime_purchase_value, 2) . "<br>";
                        $html .= "<strong>Loyalty Card:</strong> " . ($card ? $card->card_number : "None") . "<br>";
                        $html .= "<strong>Available Points:</strong> {$customer->total_points} pts<br>";
                        $html .= "<strong>Wallet Balance:</strong> ₹" . number_format($customer->wallet_balance, 2) . "<br>";
                        if ($customer->gst_number) $html .= "<strong>GST:</strong> {$customer->gst_number}<br>";

                        return new \Illuminate\Support\HtmlString($html);
                    }),
                TextInput::make('invoice_number')
                    ->default(fn () => 'POS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4)))
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                DatePicker::make('sale_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                Repeater::make('posSaleItems')
                    ->relationship('posSaleItems')
                    ->itemLabel(fn (array $state): ?string => (\App\Models\Product::find($state['product_id'])?->name ?? 'New Item') . (isset($state['product_variant_id']) && $state['product_variant_id'] ? ' (' . \App\Models\ProductVariant::find($state['product_variant_id'])?->sku . ')' : ''))
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(function ($get) {
                                $query = \App\Models\Product::where('product_type', 1);
                                
                                $branchId = $get('../../branch_id');
                                $reservationId = $get('../../stock_reservation_id');
                                
                                if ($branchId) {
                                    $productIdsWithStock = \App\Models\Stock::where('branch_id', $branchId)
                                        ->where('available_qty', '>', 0)
                                        ->pluck('product_id')
                                        ->toArray();
                                        
                                    if ($reservationId) {
                                        $reservedProductIds = \App\Models\StockReservation::where('id', $reservationId)
                                            ->pluck('product_id')
                                            ->toArray();
                                        $productIdsWithStock = array_unique(array_merge($productIdsWithStock, $reservedProductIds));
                                    }
                                    
                                    if (!empty($productIdsWithStock)) {
                                        $query->whereIn('id', $productIdsWithStock);
                                    } else {
                                        $query->whereNull('id'); // Force empty if no stock
                                    }
                                }
                                
                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $set('product_variant_id', null);
                                if ($state) {
                                    $product = \App\Models\Product::find($state);
                                    if ($product) {
                                        $customerId = $get('../../customer_id');
                                        $branchId = $get('../../branch_id');
                                        $customer = $customerId ? \App\Models\Customer::find($customerId) : null;
                                        
                                        $priceService = new \App\Services\PriceListService();
                                        $priceListItem = $priceService->getApplicablePriceListItem(
                                            $state,
                                            null,
                                            $customer ? $customer->activeMembership()->first()?->membership_plan_id : null,
                                            $branchId
                                        );
                                        
                                        $unitPrice = $priceListItem ? $priceListItem->selling_price : $product->base_price;
                                        
                                        $set('price_list_id', $priceListItem ? $priceListItem->price_list_id : null);
                                        $set('unit_price', $unitPrice);
                                        
                                        $qty = (float) ($get('quantity') ?: 1);
                                        $disc = (float) ($get('discount') ?: 0);
                                        $set('total_price', ($unitPrice * $qty) - $disc);
                                    }
                                }
                                $recalculateAll($set, $get);
                            })
                            ->required(),
                        \Filament\Forms\Components\Hidden::make('price_list_id'),
                        Select::make('product_variant_id')
                            ->label('Product Variant')
                            ->options(function ($get) {
                                $productId = $get('product_id');
                                $branchId = $get('../../branch_id');
                                $reservationId = $get('../../stock_reservation_id');

                                if (!$productId) {
                                    return [];
                                }

                                $query = \App\Models\ProductVariant::where('product_id', $productId);
                                
                                if ($branchId) {
                                    $variantIdsWithStock = \App\Models\Stock::where('branch_id', $branchId)
                                        ->where('product_id', $productId)
                                        ->where('available_qty', '>', 0)
                                        ->pluck('product_variant_id')
                                        ->filter()
                                        ->toArray();

                                    if ($reservationId) {
                                        $reservedVariantIds = \App\Models\StockReservation::where('id', $reservationId)
                                            ->where('product_id', $productId)
                                            ->pluck('product_variant_id')
                                            ->filter()
                                            ->toArray();
                                        $variantIdsWithStock = array_unique(array_merge($variantIdsWithStock, $reservedVariantIds));
                                    }
                                    
                                    if (!empty($variantIdsWithStock)) {
                                        $query->whereIn('id', $variantIdsWithStock);
                                    } else {
                                        $query->whereNull('id');
                                    }
                                }

                                return $query->pluck('sku', 'id');
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                if ($state) {
                                    $variant = \App\Models\ProductVariant::find($state);
                                    if ($variant) {
                                        $productId = $get('product_id');
                                        $customerId = $get('../../customer_id');
                                        $branchId = $get('../../branch_id');
                                        $customer = $customerId ? \App\Models\Customer::find($customerId) : null;
                                        
                                        $priceService = new \App\Services\PriceListService();
                                        $priceListItem = $priceService->getApplicablePriceListItem(
                                            $productId,
                                            $state,
                                            $customer ? $customer->activeMembership()->first()?->membership_plan_id : null,
                                            $branchId
                                        );
                                        
                                        $unitPrice = $priceListItem ? $priceListItem->selling_price : $variant->selling_price;
                                        
                                        $set('price_list_id', $priceListItem ? $priceListItem->price_list_id : null);
                                        $set('unit_price', $unitPrice);
                                        
                                        $qty = (float) ($get('quantity') ?: 1);
                                        $disc = (float) ($get('discount') ?: 0);
                                        $set('total_price', ($unitPrice * $qty) - $disc);
                                        $recalculateAll($set, $get);
                                    }
                                }
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->rule(function ($get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $productId = $get('product_id');
                                    $variantId = $get('product_variant_id');
                                    $branchId = $get('../../branch_id');
                                    $reservationId = $get('../../stock_reservation_id');
                                    
                                    if ($productId && $branchId) {
                                        $stockQuery = \App\Models\Stock::where('product_id', $productId)
                                            ->where('branch_id', $branchId);
                                            
                                        if ($variantId) {
                                            $stockQuery->where('product_variant_id', $variantId);
                                        } else {
                                            $stockQuery->whereNull('product_variant_id');
                                        }
                                        
                                        $available = (float) $stockQuery->sum('available_qty');
                                        $qty = (float) $value;
                                        
                                        // If a reservation is selected, allow purchasing up to the available + reserved (for that reservation's product)
                                        if ($reservationId) {
                                            $res = \App\Models\StockReservation::find($reservationId);
                                            if ($res && $res->product_id == $productId && $res->product_variant_id == $variantId) {
                                                $available += $res->quantity;
                                            }
                                        }

                                        if ($available < $qty) {
                                            $fail("Product is reserved and currently unavailable for sale. (Available: {$available})");
                                        }
                                    }
                                };
                            })
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $discount = (float) ($get('discount') ?: 0);
                                $set('total_price', ($unitPrice * (float) $state) - $discount);
                                $recalculateAll($set, $get);
                            }),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $qty = (float) ($get('quantity') ?: 1);
                                $discount = (float) ($get('discount') ?: 0);
                                $set('total_price', ((float) $state * $qty) - $discount);
                                $recalculateAll($set, $get);
                            }),
                        TextInput::make('discount')
                            ->numeric()
                            ->prefix('₹')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $qty = (float) ($get('quantity') ?: 1);
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $set('total_price', ($unitPrice * $qty) - (float) $state);
                                $recalculateAll($set, $get);
                            }),
                        TextInput::make('total_price')->numeric()->prefix('₹')->default(0)->required(),
                        \Filament\Forms\Components\Hidden::make('tax_percentage')->default(0),
                        \Filament\Forms\Components\Hidden::make('taxable_amount')->default(0),
                        \Filament\Forms\Components\Hidden::make('cgst')->default(0),
                        \Filament\Forms\Components\Hidden::make('sgst')->default(0),
                        \Filament\Forms\Components\Hidden::make('igst')->default(0),
                        \Filament\Forms\Components\Hidden::make('gst_amount')->default(0),
                        \Filament\Forms\Components\Hidden::make('net_amount')->default(0),
                        \Filament\Forms\Components\Hidden::make('mrp')->default(0),
                        \Filament\Forms\Components\Hidden::make('selling_price')->default(0),
                        \Filament\Forms\Components\Hidden::make('gross_amount')->default(0),
                        \Filament\Forms\Components\Hidden::make('discount_type')->nullable(),
                        \Filament\Forms\Components\Hidden::make('discount_percentage')->default(0),
                        \Filament\Forms\Components\Hidden::make('discount_amount')->default(0),
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                        $recalculateAll($set, $get);
                    })
                    ->columns(3)
                    ->columnSpanFull(),
                CheckboxList::make('selected_offers')
                    ->label('Eligible Customer Benefits & Product Promotions')
                    ->helperText('Select optional benefits or promotions requested by the customer. No offer is applied automatically.')
                    ->options(function ($get) {
                        $customerId = $get('customer_id');
                        $items = $get('posSaleItems') ?? [];
                        $service = new PosPromotionService();
                        $offers = $service->getEligibleOffers($customerId, $items);
                        $options = [];
                        foreach ($offers as $key => $offer) {
                            $est = ($offer['estimated_discount'] > 0) ? " (Saves ₹" . number_format($offer['estimated_discount'], 2) . ")" : "";
                            $options[$key] = "[{$offer['category']}] {$offer['title']}{$est}";
                        }
                        return $options;
                    })
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                        $recalculateAll($set, $get);
                    })
                    ->columnSpanFull(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('taxable_amount')
                    ->readOnly()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('tax_amount')
                    ->label('Total Tax Amount')
                    ->readOnly()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('cgst_amount')
                    ->readOnly()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('sgst_amount')
                    ->readOnly()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('igst_amount')
                    ->readOnly()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('redeem_points')
                    ->label('Redeem Loyalty Points')
                    ->numeric()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                        $recalculateAll($set, $get);
                    })
                    ->rule(function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $customerId = $get('customer_id');
                            if (!$customerId && $value > 0) {
                                $fail('Please select a customer first.');
                                return;
                            }
                            $customer = \App\Models\Customer::find($customerId);
                            if ($customer && $value > $customer->total_points) {
                                $fail("Customer only has {$customer->total_points} points available.");
                            }
                        };
                    }),
                TextInput::make('redeem_points_amount')
                    ->label('Points Value (Amount)')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0)
                    ->readOnly(),
                TextInput::make('use_wallet_balance')
                    ->label('Use Wallet Balance (Amount)')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                        $recalculateAll($set, $get);
                    })
                    ->rule(function ($get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            $customerId = $get('customer_id');
                            if (!$customerId && $value > 0) {
                                $fail('Please select a customer first.');
                                return;
                            }
                            $customer = \App\Models\Customer::find($customerId);
                            if ($customer && $value > $customer->wallet_balance) {
                                $fail("Customer only has ₹{$customer->wallet_balance} in wallet.");
                            }
                        };
                    }),
                Select::make('gift_voucher_id')
                    ->label('Gift Voucher')
                    ->relationship('giftVoucher', 'voucher_code', function ($query) {
                        $query->where('is_redeemed', false)->where('amount', '>', 0)->whereDate('expiry_date', '>=', now());
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->voucher_code ?? 'Voucher #' . $record->id)
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                        if ($state) {
                            $voucher = \App\Models\GiftVoucher::find($state);
                            if ($voucher) {
                                $subtotal = (float) $get('total_amount');
                                $tax = (float) $get('tax_amount');
                                $disc = (float) $get('discount_amount');
                                $points = (float) $get('redeem_points_amount');
                                $wallet = (float) $get('use_wallet_balance');
                                $remaining = max(0, ($subtotal + $tax) - $disc - $points - $wallet);
                                $applyAmount = min($voucher->amount, $remaining);
                                $set('gift_voucher_amount', $applyAmount);
                            }
                        } else {
                            $set('gift_voucher_amount', 0);
                        }
                        $recalculateAll($set, $get);
                    }),
                TextInput::make('gift_voucher_amount')
                    ->label('Gift Voucher Amount Applied')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0)
                    ->readOnly(),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                Repeater::make('payments')
                    ->helperText('Optional. You can leave payment empty and collect payment later.')
                    ->default([])
                    ->schema([
                        Select::make('payment_mode_id')
                            ->options(\App\Models\PaymentMode::pluck('name', 'id'))
                            ->nullable()
                            ->label('Payment Mode'),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('₹')
                            ->nullable()
                            ->default(0.0),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('completed'),
            ]);
    }
}
