<?php

namespace App\Filament\Resources\CashBooks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashBookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('description'),
                TextInput::make('receipt_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('payment_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
