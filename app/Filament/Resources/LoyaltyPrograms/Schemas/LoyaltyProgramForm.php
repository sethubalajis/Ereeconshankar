<?php

namespace App\Filament\Resources\LoyaltyPrograms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LoyaltyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('points_per_amount')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('minimum_points_to_redeem')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('value_per_point')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
