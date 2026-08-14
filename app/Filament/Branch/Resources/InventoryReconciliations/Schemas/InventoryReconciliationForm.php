<?php

namespace App\Filament\Branch\Resources\InventoryReconciliations\Schemas;

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
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
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
}
