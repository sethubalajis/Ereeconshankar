<?php

namespace App\Filament\Resources\CreditNotes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CreditNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('note_number')
                    ->required(),
                DatePicker::make('note_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('reason')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('issued'),
            ]);
    }
}
