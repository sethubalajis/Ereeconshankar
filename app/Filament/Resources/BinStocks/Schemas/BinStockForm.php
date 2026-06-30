<?php

namespace App\Filament\Resources\BinStocks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BinStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('bin_id')->relationship('bin', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
