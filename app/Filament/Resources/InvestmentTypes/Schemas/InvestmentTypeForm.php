<?php

namespace App\Filament\Resources\InvestmentTypes\Schemas;

use Filament\Schemas\Schema;

class InvestmentTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('section')
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('max_limit')
                    ->numeric()
                    ->nullable(),
                \Filament\Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(true),
            ]);
    }
}
