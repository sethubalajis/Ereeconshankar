<?php

namespace App\Filament\Resources\JournalEntries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JournalEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('journal_id')->relationship('journal', 'journal_number')
                    ->required(),
                \Filament\Forms\Components\Select::make('chart_of_account_id')->relationship('chartOfAccount', 'account_name')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('debit')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('credit')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
            ]);
    }
}
