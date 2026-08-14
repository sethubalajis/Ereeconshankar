<?php

namespace App\Filament\Branch\Resources\LoyaltyCards\Schemas;

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
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated on save'),
                DatePicker::make('issue_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('expiry_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
