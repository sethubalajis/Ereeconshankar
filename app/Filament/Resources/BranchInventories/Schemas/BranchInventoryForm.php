<?php

namespace App\Filament\Resources\BranchInventories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchInventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateQuantity = function ($get, $set) {
            $branchId = $get('branch_id');
            $warehouseId = $get('warehouse_id');
            $productId = $get('product_id');
            $variantId = $get('product_variant_id');
            if ($branchId && $warehouseId && $productId && $variantId) {
                $stockQty = \App\Models\Stock::where('branch_id', $branchId)
                    ->where('warehouse_id', $warehouseId)
                    ->where('product_id', $productId)
                    ->where('product_variant_id', $variantId)
                    ->sum('quantity');
                $set('quantity', (float) $stockQty);
            } else {
                $set('quantity', 0);
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $get) use ($updateQuantity) {
                        if ($state) {
                            $warehouseId = \App\Models\Warehouse::defaultIdForBranch((int) $state) 
                                ?? \App\Models\Warehouse::where('branch_id', $state)->first()?->id;
                            $set('warehouse_id', $warehouseId);
                        } else {
                            $set('warehouse_id', null);
                        }
                        $updateQuantity($get, $set);
                    })
                    ->required(),
                \Filament\Forms\Components\Select::make('warehouse_id')
                    ->relationship('warehouse', 'name', function (\Illuminate\Database\Eloquent\Builder $query, $get) {
                        if ($get('branch_id')) {
                            return $query->where('branch_id', $get('branch_id'));
                        }
                        return $query;
                    })
                    ->live()
                    ->required()
                    ->afterStateUpdated($updateQuantity),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->live()
                    ->afterStateUpdated(function ($set, $get) use ($updateQuantity) {
                        $set('product_variant_id', null);
                        $updateQuantity($get, $set);
                    })
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')
                    ->relationship('productVariant', 'sku', function (\Illuminate\Database\Eloquent\Builder $query, $get) {
                        return $query->where('product_id', $get('product_id'));
                    })
                    ->live()
                    ->required()
                    ->afterStateUpdated($updateQuantity),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
