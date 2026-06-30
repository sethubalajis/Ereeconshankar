<?php

namespace App\Filament\Resources\TransferItems\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransferItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('stock_transfer_id')->relationship('stockTransfer', 'vehicle_number')
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
