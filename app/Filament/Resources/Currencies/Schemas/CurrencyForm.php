<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->required()
                    ->maxLength(10)
                    ->placeholder('e.g. INR, USD, MYR'),
                TextInput::make('symbol')
                    ->maxLength(10)
                    ->placeholder('e.g. ₹, $, €, RM')
                    ->datalist([
                        '₹',
                        '$',
                        '€',
                        '£',
                        'RM',
                        'S$',
                        'AED',
                        '¥',
                        'CHF',
                        'CAD',
                        'AUD',
                    ])
                    ->helperText('Select from dropdown suggestions or type a custom symbol.'),
                TextInput::make('exchange_rate')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
