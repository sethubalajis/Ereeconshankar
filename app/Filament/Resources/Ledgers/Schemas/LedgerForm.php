<?php

namespace App\Filament\Resources\Ledgers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LedgerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('chart_of_account_id')->relationship('chartOfAccount', 'account_name')
                    ->required(),
                DatePicker::make('transaction_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('description'),
                TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('credit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
