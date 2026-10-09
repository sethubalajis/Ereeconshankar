<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CountryForm
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
                    ->placeholder('e.g. IND, MYS, 8500'),
                TextInput::make('phone_code')
                    ->tel()
                    ->maxLength(10),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
