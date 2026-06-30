<?php

namespace App\Filament\Resources\LoyaltyPoints\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LoyaltyPointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('transaction_type')
                    ->required(),
                TextInput::make('points')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('description'),
                DatePicker::make('transaction_date')
                    ->required(),
            ]);
    }
}
