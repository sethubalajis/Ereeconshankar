<?php

namespace App\Filament\Resources\Journals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JournalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('journal_number')
                    ->required(),
                DatePicker::make('journal_date')
                    ->required(),
                TextInput::make('reference'),
                TextInput::make('total_debit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_credit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('posted'),
            ]);
    }
}
