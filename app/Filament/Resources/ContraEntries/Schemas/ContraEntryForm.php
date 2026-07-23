<?php

namespace App\Filament\Resources\ContraEntries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContraEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                \Filament\Forms\Components\Select::make('from_account_id')->relationship('fromAccount', 'account_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('to_account_id')->relationship('toAccount', 'account_name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('description'),
            ]);
    }
}
