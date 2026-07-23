<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->required(),
                TextInput::make('account_number')
                    ->required(),
                TextInput::make('ifsc_code'),
                TextInput::make('branch_name'),
                TextInput::make('account_type'),
                TextInput::make('opening_balance')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
