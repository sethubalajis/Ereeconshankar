<?php

namespace App\Filament\Resources\ChartOfAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChartOfAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('account_group_id')->relationship('accountGroup', 'name')
                    ->required(),
                TextInput::make('account_code')
                    ->required(),
                TextInput::make('account_name')
                    ->required(),
                TextInput::make('account_type')
                    ->required(),
                TextInput::make('opening_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('current_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
