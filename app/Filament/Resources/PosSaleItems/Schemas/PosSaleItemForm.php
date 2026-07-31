<?php

namespace App\Filament\Resources\PosSaleItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PosSaleItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('pos_sale_id')->relationship('posSale', 'invoice_number')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name', fn ($query) => $query->whereIn('product_type', ['Sellable', 'Inventory']))
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
            ]);
    }
}
