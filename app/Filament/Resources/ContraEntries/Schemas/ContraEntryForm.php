<?php

namespace App\Filament\Resources\ContraEntries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
                Select::make('from_account_id')
                    ->relationship('fromAccount', 'account_name')
                    ->label('From Account')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('to_account_id')
                    ->relationship('toAccount', 'account_name')
                    ->label('To Account')
                    ->searchable()
                    ->preload()
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