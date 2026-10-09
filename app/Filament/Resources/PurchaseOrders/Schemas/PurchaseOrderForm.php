<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;
use App\Models\ProductVariant;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vendor_id')
                    ->relationship('vendor', 'name')
                    ->searchable()
                    ->required()
                    ->live(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('po_number')
                    ->default('Auto-generated')
                    ->disabled()
                    ->dehydrated(false),
                Select::make('quotation_number')
                    ->options(function (callable $get) {
                        $vendorId = $get('vendor_id');
                        $query = \App\Models\Quotation::whereNotNull('quotation_number');
                        if ($vendorId) {
                            $query->where('vendor_id', $vendorId);
                        }
                        return $query->get()->mapWithKeys(function ($quote) {
                            return [$quote->quotation_number => "{$quote->quotation_number} (" . ucfirst($quote->status) . " - ₹{$quote->total_amount})"];
                        });
                    })
                    ->searchable()
                    ->nullable()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) return;
                        $quotation = \App\Models\Quotation::with('items')->where('quotation_number', $state)->first();
                        if ($quotation) {
                            $set('vendor_id', $quotation->vendor_id);
                            if ($quotation->branch_id) {
                                $set('branch_id', $quotation->branch_id);
                            }
                            $items = [];
                            foreach ($quotation->items as $qItem) {
                                $items[] = [
                                    'product_id' => $qItem->product_id,
                                    'product_variant_id' => $qItem->product_variant_id,
                                    'quantity' => $qItem->quantity,
                                    'unit_price' => $qItem->unit_price,
                                    'total_price' => $qItem->total_price,
                                ];
                            }
                            $set('items', $items);
                            $set('total_amount', $quotation->total_amount);
                        }
                    }),
                DatePicker::make('po_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('expected_delivery_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_amount')
                    ->required()
                    ->prefix('₹')
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('draft'),
                Repeater::make('items')
                    ->relationship('items')
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['product_id']) && $state['product_id'] 
                            ? 'Item: ' . (\App\Models\Product::find($state['product_id'])?->name ?? 'Product #' . $state['product_id']) 
                            : 'New Item'
                    )
                    ->collapsible()
                    ->cloneable()
                    ->addActionLabel('+ Add Item')
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $items = $get('items') ?? [];
                        $total = collect($items)->sum('total_price');
                        $set('total_amount', $total);
                    })
                    ->schema([
                        Select::make('product_id')->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),
                        Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku')
                            ->options(function (callable $get) {
                                if (!$get('product_id')) return [];
                                return ProductVariant::where('product_id', $get('product_id'))->pluck('sku', 'id');
                            })
                            ->searchable()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $variant = \App\Models\ProductVariant::find($state);
                                    if ($variant) {
                                        $set('unit_price', $variant->cost_price);
                                        $set('total_price', $variant->cost_price * (float)($get('quantity') ?: 1));
                                    }
                                }
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $unitPrice = (float)($get('unit_price') ?: 0);
                                $set('total_price', $unitPrice * (float)$state);
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        TextInput::make('unit_price')
                            ->required()
                            ->prefix('₹')
                            ->numeric()
                            ->default(0.0)
                            ->live(debounce: 500)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = (float)($get('quantity') ?: 1);
                                $set('total_price', (float)$state * $qty);
                                $items = $get('../../items') ?? [];
                                $total = collect($items)->sum('total_price');
                                $set('../../total_amount', $total);
                            }),
                        TextInput::make('total_price')
                            ->required()
                            ->prefix('₹')
                            ->numeric()
                            ->default(0.0)
                            ->readOnly(),
                    ])
                    ->columns(5)
                    ->columnSpanFull()
                    ->defaultItems(1),
            ]);
    }
}