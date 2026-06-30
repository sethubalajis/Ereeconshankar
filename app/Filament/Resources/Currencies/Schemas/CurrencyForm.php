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
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('symbol'),
                TextInput::make('exchange_rate')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
