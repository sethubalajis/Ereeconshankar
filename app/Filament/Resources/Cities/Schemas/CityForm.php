<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('state_id')->relationship('state', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
