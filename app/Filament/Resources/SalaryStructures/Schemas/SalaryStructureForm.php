<?php

namespace App\Filament\Resources\SalaryStructures\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SalaryStructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('basic_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('hra_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('special_allowance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
