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
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('po_number')
                    ->default('Auto-generated')
                    ->disabled()
                    ->dehydrated(false),
                DatePicker::make('po_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('expected_delivery_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('total_amount')
                    ->required()
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
                    ->live()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $items = $get('items') ?? [];
                        $total = collect($items)->sum('total_price');
                        $set('total_amount', $total);
                    })
                    ->schema([
                        Select::make('product_id')->relationship('product', 'name')
                            ->required()
                            ->live(),
                        Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku')
                            ->options(function (callable $get) {
                                if (!$get('product_id')) return [];
                                return ProductVariant::where('product_id', $get('product_id'))->pluck('sku', 'id');
                            })
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
