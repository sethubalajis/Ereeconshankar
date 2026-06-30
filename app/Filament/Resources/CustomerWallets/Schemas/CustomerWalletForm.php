<?php

namespace App\Filament\Resources\CustomerWallets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerWalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('transaction_type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('description'),
                DatePicker::make('transaction_date')
                    ->required(),
            ]);
    }
}
