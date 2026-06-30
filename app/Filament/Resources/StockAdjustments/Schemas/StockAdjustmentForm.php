<?php

namespace App\Filament\Resources\StockAdjustments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockAdjustmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name'),
                \Filament\Forms\Components\Select::make('product_id')->relationship('product', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('product_variant_id')->relationship('productVariant', 'sku'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('reason'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
