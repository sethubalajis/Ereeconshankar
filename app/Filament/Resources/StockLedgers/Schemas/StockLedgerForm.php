<?php

namespace App\Filament\Resources\StockLedgers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockLedgerForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateQuantities = function ($set, $get) {
            $productId = $get('product_id');
            $variantId = $get('product_variant_id');
            $branchId = $get('branch_id');
            $warehouseId = $get('warehouse_id');

            if ($productId && $branchId) {
                $query = \App\Models\Stock::where('product_id', $productId)
                    ->where('branch_id', $branchId);
                
                if ($variantId) {
                    $query->where('product_variant_id', $variantId);
                }
                if ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                }
                
                $stock = $query->first();
                $openingQty = $stock ? (float) $stock->quantity : 0.0;
                $set('opening_qty', $openingQty);
            } else {
                $set('opening_qty', 0.0);
            }

            $opening = (float) ($get('opening_qty') ?: 0);
            $inward = (float) ($get('inward_qty') ?: 0);
            $outward = (float) ($get('outward_qty') ?: 0);
            
            $set('closing_qty', $opening + $inward - $outward);
        };

        $recalcClosing = function ($set, $get) {
            $opening = (float) ($get('opening_qty') ?: 0);
            $inward = (float) ($get('inward_qty') ?: 0);
            $outward = (float) ($get('outward_qty') ?: 0);
            $set('closing_qty', $opening + $inward - $outward);
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateQuantities),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku')
                    ->live()
                    ->afterStateUpdated($updateQuantities),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateQuantities),
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name')
                    ->live()
                    ->afterStateUpdated($updateQuantities),
                DatePicker::make('transaction_date')
                    ->required(),
                TextInput::make('opening_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated($recalcClosing),
                TextInput::make('inward_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated($recalcClosing),
                TextInput::make('outward_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live()
                    ->afterStateUpdated($recalcClosing),
                TextInput::make('closing_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readonly(),
            ]);
    }
}
