<?php

namespace App\Filament\Resources\LoyaltyCards\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LoyaltyCardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('card_number')
                    ->required(),
                DatePicker::make('issue_date')
                    ->required(),
                DatePicker::make('expiry_date'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
