<?php

namespace App\Filament\Resources\CashClosings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashClosingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('cash_register_id')->relationship('cashRegister', 'status')
                    ->required(),
                DateTimePicker::make('closing_time')
                    ->required(),
                TextInput::make('closing_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('variance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
