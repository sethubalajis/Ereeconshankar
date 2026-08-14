<?php

namespace App\Filament\Resources\InventoryReconciliations\Schemas;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryReconciliationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('inventory_count_id')->relationship('inventoryCount', 'count_code')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        self::updateSystemQty($set, $get);
                    }),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('product_variant_id', null);
                        self::updateSystemQty($set, $get);
                    }),
                \Filament\Forms\Components\Select::make('product_variant_id')
                    ->relationship('productVariant', 'sku', function ($query, Get $get) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $query->where('product_id', $productId);
                        }
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        self::updateSystemQty($set, $get);
                    }),
                TextInput::make('system_qty')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->default(0.0),
                TextInput::make('physical_qty')
                    ->required()
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, Get $get, $state): void {
                        $systemQty = (float) ($get('system_qty') ?? 0);
                        $physicalQty = (float) ($state ?? 0);
                        $set('variance', $physicalQty - $systemQty);
                    })
                    ->default(0.0),
                TextInput::make('variance')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->dehydrated(true)
                    ->formatStateUsing(fn ($state, Get $get) => (float) ($get('physical_qty') ?? 0) - (float) ($get('system_qty') ?? 0))
                    ->default(0.0),
            ]);
    }

    public static function updateSystemQty(Set $set, Get $get): void
    {
        $inventoryCountId = $get('inventory_count_id');
        $productId = $get('product_id');
        $productVariantId = $get('product_variant_id');

        if (!$inventoryCountId || !$productId) {
            $set('system_qty', 0);
            $set('variance', (float) ($get('physical_qty') ?? 0));
            return;
        }

        $inventoryCount = \App\Models\InventoryCount::find($inventoryCountId);
        
        if (!$inventoryCount || !$inventoryCount->branch_id) {
            $set('system_qty', 0);
            $set('variance', (float) ($get('physical_qty') ?? 0));
            return;
        }

        $stockQuery = \App\Models\Stock::where('branch_id', $inventoryCount->branch_id)
            ->where('product_id', $productId);

        if ($productVariantId) {
            $stockQuery->where('product_variant_id', $productVariantId);
        } else {
            $stockQuery->whereNull('product_variant_id');
        }

        $systemQty = (float) $stockQuery->sum('quantity');
        $physicalQty = (float) ($get('physical_qty') ?? 0);
        
        $set('system_qty', $systemQty);
        $set('variance', $physicalQty - $systemQty);
    }
}
