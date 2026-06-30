<?php

namespace App\Filament\Resources\StockTransactions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('stock_id')->relationship('stock', 'id')
                    ->required(),
                TextInput::make('transaction_type')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\TextInput::make('reference_id')->numeric()
                    ,
                TextInput::make('reference_type'),
            ]);
    }
}
