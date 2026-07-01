<?php

namespace App\Filament\Resources\VendorPriceLists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorPriceListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                TextInput::make('agreed_price')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                DatePicker::make('valid_from')
                    ->default(now()),
                DatePicker::make('valid_to')
                    ->default(now()),
            ]);
    }
}
