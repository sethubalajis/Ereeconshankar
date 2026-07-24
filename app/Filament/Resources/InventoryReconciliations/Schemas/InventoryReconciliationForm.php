<?php

namespace App\Filament\Resources\InventoryReconciliations\Schemas;

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
                    ->default(0.0),
                TextInput::make('physical_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('variance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
