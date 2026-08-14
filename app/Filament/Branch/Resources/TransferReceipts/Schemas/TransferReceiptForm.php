<?php

namespace App\Filament\Branch\Resources\TransferReceipts\Schemas;

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
                 ->label('Vehicle Number')
                    ->required(),
                DatePicker::make('receive_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Select::make('received_by')
                    ->label('Received by')
                    ->relationship('receivedBy', 'name'),
                \Filament\Forms\Components\Select::make('status')
                    ->label('Status')
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
