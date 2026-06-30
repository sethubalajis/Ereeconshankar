<?php

namespace App\Filament\Resources\TransferReceipts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransferReceiptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('stock_transfer_id')->relationship('stockTransfer', 'vehicle_number')
                    ->required(),
                DatePicker::make('receive_date')
                    ->required(),
                TextInput::make('received_by')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('received'),
            ]);
    }
}
