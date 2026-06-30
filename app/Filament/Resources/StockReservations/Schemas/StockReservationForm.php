<?php

namespace App\Filament\Resources\StockReservations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\TextInput::make('reference_id')->numeric()
                    ,
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
