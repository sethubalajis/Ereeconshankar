<?php

namespace App\Filament\Resources\PosSales\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PosSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateParentTotals = function ($set, $get) {
            $items = $get('../../posSaleItems') ?? [];
            $totalAmount = 0;
            $totalDiscount = 0;
            foreach ($items as $item) {
                $totalAmount += (float) ($item['total_price'] ?? 0);
                $totalDiscount += (float) ($item['discount'] ?? 0);
            }
            $taxAmount = $totalAmount * 0.10; // example 10% tax
            
            $redeemPointsAmount = (float) ($get('../../redeem_points_amount') ?? 0);
            $walletAmount = (float) ($get('../../use_wallet_balance') ?? 0);
            $voucherAmount = (float) ($get('../../gift_voucher_amount') ?? 0);
            
            $netAmount = ($totalAmount + $taxAmount) - $redeemPointsAmount - $walletAmount - $voucherAmount;
            
            $set('../../total_amount', $totalAmount);
            $set('../../discount_amount', $totalDiscount);
            $set('../../tax_amount', $taxAmount);
            $set('../../net_amount', max(0, $netAmount));
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('pos_terminal_id')->relationship('posTerminal', 'terminal_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('customer_id')
                    ->label('Customer (Search by Name, Mobile, or Loyalty Card)')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return \App\Models\Customer::where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhereHas('loyaltyCards', function ($query) use ($search) {
                                $query->where('card_number', 'like', "%{$search}%");
                            })
                            ->limit(50)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => \App\Models\Customer::find($value)?->name)
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->unique('customers', 'phone')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        \Filament\Forms\Components\Select::make('customer_group_id')
                            ->relationship('customerGroup', 'name')
                            ->searchable()
                            ->preload(),
                    ])
                    ->live(),
                \Filament\Forms\Components\Placeholder::make('membership_placeholder')
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
                        
                        $program = \App\Models\LoyaltyProgram::where('status', true)
                            ->where(function($q) use ($customer) {
                                $q->where('membership_plan_id', $customer->membership_plan_id)
                                  ->orWhere('customer_group_id', $customer->customer_group_id)
                                  ->orWhere(function($q2) {
                                      $q2->whereNull('membership_plan_id')->whereNull('customer_group_id');
                                  });
                            })->orderBy('id', 'desc')->first();

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
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('net_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\Repeater::make('posSaleItems')
                    ->relationship('posSaleItems')
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')->required(),
                        \Filament\Forms\Components\Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku')
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($updateParentTotals) {
                                if ($state) {
                                    $variant = \App\Models\ProductVariant::find($state);
                                    if ($variant) {
                                        $customerId = $get('../../customer_id');
                                        $discount = 0;
                                        if ($customerId) {
                                            $group = \App\Models\Customer::with('customerGroup')->find($customerId)?->customerGroup;
                                            if ($group && $group->discount_percentage) {
                                                $discount = $variant->selling_price * ($group->discount_percentage / 100);
                                            }
                                        }
                                        $set('unit_price', $variant->selling_price);
                                        $set('discount', $discount);
                                        
                                        $qty = (float) ($get('quantity') ?: 1);
                                        $set('total_price', ($variant->selling_price * $qty) - $discount);
                                        $updateParentTotals($set, $get);
                                    }
                                }
                            }),
                        \Filament\Forms\Components\Placeholder::make('promotion_info')
                            ->label('Active Promotions & Discounts')
                            ->content(function ($get) {
                                $variantId = $get('product_variant_id');
                                if (! $variantId) return 'Select a product to view applicable promotions.';
                                
                                $variant = \App\Models\ProductVariant::find($variantId);
                                if (! $variant) return 'Invalid product.';

                                $html = '';
                                
                                $customerId = $get('../../customer_id');
                                if ($customerId) {
                                    $group = \App\Models\Customer::with('customerGroup')->find($customerId)?->customerGroup;
                                    if ($group && $group->discount_percentage) {
                                        $html .= "<span class='text-primary-600 font-medium'>Customer Group Discount:</span> {$group->discount_percentage}%<br>";
                                    }
                                }

                                $activeDiscounts = \App\Models\DiscountRule::where('status', true)->whereDate('end_date', '>=', now())->get();
                                foreach($activeDiscounts as $rule) {
                                    $val = $rule->discount_percentage ? "{$rule->discount_percentage}%" : "₹{$rule->discount_amount}";
                                    $html .= "<span class='text-success-600 font-medium'>Discount Rule ({$rule->code}):</span> {$rule->name} - {$val}<br>";
                                }

                                $festivalOffers = \App\Models\FestivalOffer::where('status', true)->whereDate('end_date', '>=', now())->get();
                                foreach($festivalOffers as $offer) {
                                    $val = $offer->discount_percentage ? "{$offer->discount_percentage}%" : "₹{$offer->discount_amount}";
                                    $html .= "<span class='text-warning-600 font-medium'>Festival Offer:</span> {$offer->name} - {$val}<br>";
                                }
                                
                                if (! $html) return 'No active promotions available.';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($updateParentTotals) {
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $discount = (float) ($get('discount') ?: 0);
                                $set('total_price', ($unitPrice * (float) $state) - $discount);
                                $updateParentTotals($set, $get);
                            }),
                        TextInput::make('unit_price')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($updateParentTotals) {
                                $qty = (float) ($get('quantity') ?: 1);
                                $discount = (float) ($get('discount') ?: 0);
                                $set('total_price', ((float) $state * $qty) - $discount);
                                $updateParentTotals($set, $get);
                            }),
                        TextInput::make('discount')
                            ->numeric()
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get) use ($updateParentTotals) {
                                $qty = (float) ($get('quantity') ?: 1);
                                $unitPrice = (float) ($get('unit_price') ?: 0);
                                $set('total_price', ($unitPrice * $qty) - (float) $state);
                                $updateParentTotals($set, $get);
                            }),
                        TextInput::make('total_price')->numeric()->default(0)->required(),
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $items = $state ?? [];
                        $totalAmount = 0;
                        $totalDiscount = 0;
                        foreach ($items as $item) {
                            $totalAmount += (float) ($item['total_price'] ?? 0);
                            $totalDiscount += (float) ($item['discount'] ?? 0);
                        }
                        $taxAmount = $totalAmount * 0.10; // example 10% tax
                        
                        $redeemPointsAmount = (float) ($get('redeem_points_amount') ?? 0);
                        $walletAmount = (float) ($get('use_wallet_balance') ?? 0);
                        $voucherAmount = (float) ($get('gift_voucher_amount') ?? 0);
                        $netAmount = ($totalAmount + $taxAmount) - $redeemPointsAmount - $walletAmount - $voucherAmount;
                        
                        $set('total_amount', $totalAmount);
                        $set('discount_amount', $totalDiscount);
                        $set('tax_amount', $taxAmount);
                        $set('net_amount', max(0, $netAmount));
                    })
                    ->columns(3)
                    ->columnSpanFull(),
                \Filament\Forms\Components\TextInput::make('redeem_points')
                    ->label('Redeem Loyalty Points')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $customerId = $get('customer_id');
                        $pointsAmount = 0;
                        if ($customerId && $state > 0) {
                            $customer = \App\Models\Customer::find($customerId);
                            $program = \App\Models\LoyaltyProgram::where('status', true)
                                ->where(function($q) use ($customer) {
                                    $q->where('membership_plan_id', $customer->membership_plan_id)
                                      ->orWhere('customer_group_id', $customer->customer_group_id)
                                      ->orWhere(function($q2) {
                                          $q2->whereNull('membership_plan_id')->whereNull('customer_group_id');
                                      });
                                })->orderBy('id', 'desc')->first();
                            
                            if ($program) {
                                // Validation for max points etc can go here, but for now just calculate value
                                $pointsAmount = (float) $state * $program->value_per_point;
                            }
                        }
                        
                        $set('redeem_points_amount', $pointsAmount);
                        
                        $total = (float) $get('total_amount');
                        $tax = (float) $get('tax_amount');
                        $wallet = (float) $get('use_wallet_balance');
                        $voucher = (float) $get('gift_voucher_amount');
                        $set('net_amount', max(0, ($total + $tax) - $pointsAmount - $wallet - $voucher));
                    }),
                TextInput::make('redeem_points_amount')
                    ->label('Points Value (Amount)')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),

                TextInput::make('use_wallet_balance')
                    ->label('Use Wallet Balance (Amount)')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $total = (float) $get('total_amount');
                        $tax = (float) $get('tax_amount');
                        $points = (float) $get('redeem_points_amount');
                        $voucher = (float) $get('gift_voucher_amount');
                        $set('net_amount', max(0, ($total + $tax) - $points - (float) $state - $voucher));
                    }),
                \Filament\Forms\Components\Select::make('gift_voucher_id')
                    ->label('Gift Voucher')
                    ->relationship('giftVoucher', 'voucher_code', function ($query) {
                        $query->where('is_redeemed', false)->where('amount', '>', 0)->whereDate('expiry_date', '>=', now());
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if ($state) {
                            $voucher = \App\Models\GiftVoucher::find($state);
                            if ($voucher) {
                                $total = (float) $get('total_amount');
                                $tax = (float) $get('tax_amount');
                                $points = (float) $get('redeem_points_amount');
                                $wallet = (float) $get('use_wallet_balance');
                                $remaining = max(0, ($total + $tax) - $points - $wallet);
                                $applyAmount = min($voucher->amount, $remaining);
                                $set('gift_voucher_amount', $applyAmount);
                                $set('net_amount', max(0, $remaining - $applyAmount));
                            }
                        } else {
                            $set('gift_voucher_amount', 0);
                            $total = (float) $get('total_amount');
                            $tax = (float) $get('tax_amount');
                            $points = (float) $get('redeem_points_amount');
                            $wallet = (float) $get('use_wallet_balance');
                            $set('net_amount', max(0, ($total + $tax) - $points - $wallet));
                        }
                    }),
                TextInput::make('gift_voucher_amount')
                    ->label('Gift Voucher Amount Applied')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
                \Filament\Forms\Components\Repeater::make('payments')
                    ->schema([
                        \Filament\Forms\Components\Select::make('payment_mode_id')
                            ->options(\App\Models\PaymentMode::pluck('name', 'id'))
                            ->required()
                            ->label('Payment Mode'),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->default(0.0),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
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
