<?php

namespace App\Filament\Resources\TrialBalances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TrialBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('as_of_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                \Filament\Forms\Components\Select::make('chart_of_account_id')->relationship('chartOfAccount', 'account_name')
                    ->required(),
                TextInput::make('debit_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('credit_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
