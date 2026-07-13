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
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('received_by')
                    ->numeric(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'received' => 'Received',
                        'partial' => 'Partial',
                        'discrepancy' => 'Discrepancy',
                    ])
                    ->required()
                    ->default('received'),
            ]);
    }
}
