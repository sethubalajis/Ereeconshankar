<?php

namespace App\Filament\Branch\Resources\PosSales\Schemas;

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
            $customerId = $get('customer_id') ?? $get('../../customer_id');
            $items = $get('posSaleItems') ?? $get('../../posSaleItems') ?? [];

            $grossTotal = 0;
            $manualDiscountsSum = 0;

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

            $calcResult = $promotionService->calculateSelectedOffers($selectedOfferKeys, $eligibleOffers, $subtotalAfterLineDiscounts);

            $promoDiscountAmount = $calcResult['promo_discount'];
            $pointsRedeemAmount = $calcResult['points_redeem_amount'];
            $pointsToRedeem = $calcResult['points_to_redeem'];
            $walletUseAmount = $calcResult['wallet_amount'];
            $appliedDetails = $calcResult['applied_details'];

            $totalDiscountAmount = $manualDiscountsSum + $promoDiscountAmount;
            $taxableAmount = max(0, $grossTotal - $totalDiscountAmount);
            $taxAmount = round($taxableAmount * 0.10, 2);

            $voucherAmount = (float) ($get('gift_voucher_amount') ?? $get('../../gift_voucher_amount') ?? 0);
            $netAmount = max(0, round(($grossTotal + $taxAmount) - $totalDiscountAmount - $pointsRedeemAmount - $walletUseAmount - $voucherAmount, 2));

            // Set top-level fields
            $set('total_amount', $grossTotal);
            $set('discount_amount', $totalDiscountAmount);
            $set('tax_amount', $taxAmount);
            $set('redeem_points', $pointsToRedeem);
            $set('redeem_points_amount', $pointsRedeemAmount);
            $set('use_wallet_balance', $walletUseAmount);
            $set('applied_offers', $appliedDetails);
            $set('net_amount', $netAmount);

            // Also try relative path setters when invoked from inside repeater
            $set('../../total_amount', $grossTotal);
            $set('../../discount_amount', $totalDiscountAmount);
            $set('../../tax_amount', $taxAmount);
            $set('../../redeem_points', $pointsToRedeem);
            $set('../../redeem_points_amount', $pointsRedeemAmount);
            $set('../../use_wallet_balance', $walletUseAmount);
            $set('../../applied_offers', $appliedDetails);
            $set('../../net_amount', $netAmount);
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('applied_offers')
                    ->dehydrated(fn () => \Illuminate\Support\Facades\Schema::hasColumn('pos_sales', 'applied_offers')),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
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
                    ])
                    ->live()
                    ->afterStateUpdated(function ($set, $get) use ($recalculateAll) {
                        $set('selected_offers', []);
                        $recalculateAll($set, $get);
                    }),
                Placeholder::make('membership_placeholder')
                    ->label('Customer CRM & Loyalty Info')
                    ->content(function ($get) {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return 'Select a customer to view their details, loyalty status, and wallet balance.';
                        }

                        $customer = \App\Models\Customer::with(['customerGroup', 'membershipPlan'])->find($customerId);
                        if (! $customer) return 'Customer not found.';

                        $group = $customer->customerGroup;
                        $plan = $customer->membershipPlan;
                        $card = \Illuminate\Support\Facades\DB::table('loyalty_cards')->where('customer_id', $customerId)->where('status', true)->first();
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
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name', fn ($query) => $query->whereIn('product_type', ['Sellable', 'Inventory']))
                            ->live()
                            ->afterStateUpdated(function ($set, $get) use ($recalculateAll) {
                                $set('product_variant_id', null);
                                $recalculateAll($set, $get);
                            })
                            ->required(),
                        Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku', function ($query, $get) {
                                $productId = $get('product_id');
                                if ($productId) {
                                    $query->where('product_id', $productId);
                                }
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                if ($state) {
                                    $variant = \App\Models\ProductVariant::find($state);
                                    if ($variant) {
                                        $set('unit_price', $variant->selling_price);
                                        $qty = (float) ($get('quantity') ?: 1);
                                        $disc = (float) ($get('discount') ?: 0);
                                        $set('total_price', ($variant->selling_price * $qty) - $disc);
                                        $recalculateAll($set, $get);
                                    }
                                }
                            }),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $discount = (float) ($get('discount') ?: 0);
                                $set('total_price', ($unitPrice * (float) $state) - $discount);
                                $recalculateAll($set, $get);
                            }),
                        TextInput::make('unit_price')
                            ->numeric()
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
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($recalculateAll) {
                                $qty = (float) ($get('quantity') ?: 1);
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $set('total_price', ($unitPrice * $qty) - (float) $state);
                                $recalculateAll($set, $get);
                            }),
                        TextInput::make('total_price')->numeric()->default(0)->required(),
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
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('redeem_points')
                    ->label('Redeem Loyalty Points')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('redeem_points_amount')
                    ->label('Points Value (Amount)')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                TextInput::make('use_wallet_balance')
                    ->label('Use Wallet Balance (Amount)')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                Select::make('gift_voucher_id')
                    ->label('Gift Voucher')
                    ->relationship('giftVoucher', 'voucher_code', function ($query) {
                        $query->where('is_redeemed', false)->where('amount', '>', 0)->whereDate('expiry_date', '>=', now());
                    })
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
                    ->default(0)
                    ->readOnly(),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric()
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
