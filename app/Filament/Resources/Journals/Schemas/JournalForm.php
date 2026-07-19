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
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto-generated'),
                DatePicker::make('journal_date')
                    ->default(now('Asia/Kolkata'))
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
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'posted' => 'Posted',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('posted'),
            ]);
    }
}
