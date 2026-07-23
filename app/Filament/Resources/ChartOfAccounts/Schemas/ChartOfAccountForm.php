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
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated'),
                TextInput::make('account_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('account_type')
                    ->options([
                        'asset' => 'Asset',
                        'liability' => 'Liability',
                        'equity' => 'Equity',
                        'revenue' => 'Revenue',
                        'expense' => 'Expense',
                    ])
                    ->required(),
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
            ]);
    }
}
