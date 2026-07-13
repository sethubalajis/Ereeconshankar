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
            
            $netAmount = ($totalAmount + $taxAmount) - $redeemPointsAmount - $walletAmount;
            
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
                        
                        $customer = \App\Models\Customer::with('customerGroup')->find($customerId);
                        if (! $customer) return 'Customer not found.';
                        
                        $group = $customer->customerGroup;
                        $card = \Illuminate\Support\Facades\DB::table('loyalty_cards')->where('customer_id', $customerId)->where('status', true)->first();
                        
                        $html = "<strong>Name:</strong> {$customer->name} ({$customer->phone})<br>";
                        $html .= "<strong>Group:</strong> " . ($group ? "{$group->name} ({$group->discount_percentage}% off)" : "None") . "<br>";
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
                    ->required(),
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
                        $netAmount = ($totalAmount + $taxAmount) - $redeemPointsAmount - $walletAmount;
                        
                        $set('total_amount', $totalAmount);
                        $set('discount_amount', $totalDiscount);
                        $set('tax_amount', $taxAmount);
                        $set('net_amount', max(0, $netAmount));
                    })
                    ->columns(3)
                    ->columnSpanFull(),
                TextInput::make('redeem_points_amount')
                    ->label('Redeem Loyalty Points (Amount)')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $total = (float) $get('total_amount');
                        $tax = (float) $get('tax_amount');
                        $wallet = (float) $get('use_wallet_balance');
                        $set('net_amount', max(0, ($total + $tax) - (float) $state - $wallet));
                    }),
                TextInput::make('use_wallet_balance')
                    ->label('Use Wallet Balance (Amount)')
                    ->numeric()
                    ->default(0)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $total = (float) $get('total_amount');
                        $tax = (float) $get('tax_amount');
                        $points = (float) $get('redeem_points_amount');
                        $set('net_amount', max(0, ($total + $tax) - $points - (float) $state));
                    }),
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
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
