<?php

namespace App\Filament\Resources\MerchandisePlannings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MerchandisePlanningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('financial_year_id')->relationship('financialYear', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('season_id')->relationship('season', 'name')
                    ->required(),
                TextInput::make('target_sales')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('planned_budget')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
