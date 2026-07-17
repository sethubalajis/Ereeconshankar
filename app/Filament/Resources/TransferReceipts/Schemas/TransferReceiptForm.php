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
                \Filament\Forms\Components\Select::make('received_by')
                    ->relationship('receivedBy', 'name'),
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
