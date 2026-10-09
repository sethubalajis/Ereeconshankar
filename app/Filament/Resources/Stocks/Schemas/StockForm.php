<?php

namespace App\Filament\Resources\Stocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $get) {
                        $set('product_variant_id', null);
                        self::fetchStockDetails($set, $get);
                    }),
                \Filament\Forms\Components\Select::make('product_variant_id')
                    ->relationship('productVariant', 'sku', function ($query, $get) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $query->where('product_id', $productId);
                        }
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                TextInput::make('quantity')
                    ->label('Current Stock Qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('reserved_qty')
                    ->label('Reserved Qty')
                    ->readOnly()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('available_qty')
                    ->label('Available Qty')
                    ->readOnly()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('avg_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
            ]);
    }

    public static function fetchStockDetails($set, $get)
    {
        $productId = $get('product_id');
        $variantId = $get('product_variant_id');
        $branchId = $get('branch_id');
        if ($productId && $branchId) {
            $query = \App\Models\Stock::where('product_id', $productId)
                ->where('branch_id', $branchId);
            
            if ($variantId) {
                $query->where('product_variant_id', $variantId);
            } else {
                $query->whereNull('product_variant_id');
            }

            $stocks = $query->get();
            $quantity = $stocks->sum('quantity');
            $reserved_qty = $stocks->sum('reserved_qty');
            $available_qty = $stocks->sum('available_qty');
            
            $totalCost = $stocks->sum(function ($stock) {
                return $stock->quantity * $stock->avg_cost;
            });
            $avg_cost = $quantity > 0 ? $totalCost / $quantity : 0.0;

            $set('quantity', $quantity);
            $set('reserved_qty', $reserved_qty);
            $set('available_qty', $available_qty);
            $set('avg_cost', round($avg_cost, 2));
        }
    }
}