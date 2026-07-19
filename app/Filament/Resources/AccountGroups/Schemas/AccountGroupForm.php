<?php

namespace App\Filament\Resources\AccountGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated'),
                \Filament\Forms\Components\Select::make('parent_id')->relationship('parent', 'name'),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'asset' => 'Asset',
                        'liability' => 'Liability',
                        'equity' => 'Equity',
                        'revenue' => 'Revenue',
                        'expense' => 'Expense',
                    ])
                    ->required(),
            ]);
    }
}
