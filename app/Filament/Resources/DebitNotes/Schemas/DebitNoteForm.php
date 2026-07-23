<?php

namespace App\Filament\Resources\DebitNotes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DebitNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_return_id')->relationship('purchaseReturn', 'id')
                    ->required(),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                TextInput::make('note_number')
                    ->required(),
                DatePicker::make('note_date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
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
