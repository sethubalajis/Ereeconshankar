<?php

namespace App\Filament\Resources\Receipts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('pos_sale_id')->relationship('posSale', 'invoice_number')
                    ->required(),
                TextInput::make('receipt_number')
                    ->required(),
                DateTimePicker::make('printed_at')
                    ->default(now('Asia/Kolkata')),
            ]);
    }
}
