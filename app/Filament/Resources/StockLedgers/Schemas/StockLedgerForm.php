<?php

namespace App\Filament\Resources\StockLedgers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockLedgerForm
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
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name'),
                DatePicker::make('transaction_date')
                    ->required(),
                TextInput::make('opening_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('inward_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('outward_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('closing_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
