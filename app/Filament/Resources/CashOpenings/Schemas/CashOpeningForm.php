<?php

namespace App\Filament\Resources\CashOpenings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashOpeningForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('cash_register_id')->relationship('cashRegister', 'status')
                    ->required(),
                DateTimePicker::make('opening_time')
                    ->required(),
                TextInput::make('opening_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
