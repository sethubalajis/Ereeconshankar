<?php

namespace App\Filament\Resources\StockValuations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockValuationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('valuation_date')
                    ->required(),
                TextInput::make('total_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
