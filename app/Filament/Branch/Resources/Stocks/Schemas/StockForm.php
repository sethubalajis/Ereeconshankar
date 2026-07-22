<?php

namespace App\Filament\Branch\Resources\Stocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku')
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name')
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::fetchStockDetails($set, $get)),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('avg_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
            ]);
    }

    public static function fetchStockDetails($set, $get)
    {
        $productId = $get('product_id');
        $variantId = $get('product_variant_id');
        $branchId = $get('branch_id');
        $warehouseId = $get('warehouse_id');

        if ($productId && $branchId) {
            $query = \App\Models\Stock::where('product_id', $productId)
                ->where('branch_id', $branchId);
            
            if ($variantId) {
                $query->where('product_variant_id', $variantId);
            } else {
                $query->whereNull('product_variant_id');
            }

            if ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            } else {
                $query->whereNull('warehouse_id');
            }

            $stock = $query->first();

            if ($stock) {
                $set('quantity', $stock->quantity);
                $set('avg_cost', $stock->avg_cost);
            } else {
                $set('quantity', 0);
                $set('avg_cost', 0.0);
            }
        }
    }
}
