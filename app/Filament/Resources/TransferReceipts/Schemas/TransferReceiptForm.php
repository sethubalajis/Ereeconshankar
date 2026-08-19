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
                    
                \Filament\Forms\Components\Repeater::make('transferItems')
                    ->relationship('transferItems')
                    ->schema([
                        \Filament\Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->label('Product'),
                        \Filament\Forms\Components\Select::make('product_variant_id')
                            ->relationship('productVariant', 'sku')
                            ->label('Variant'),
                        TextInput::make('dispatched_quantity')
                            ->numeric()
                            ->label('Dispatched Qty')
                            ->disabled(),
                        TextInput::make('received_quantity')
                            ->numeric()
                            ->label('Received Qty'),
                        TextInput::make('damaged_quantity')
                            ->numeric()
                            ->label('Damaged Qty'),
                        TextInput::make('shortage_quantity')
                            ->numeric()
                            ->label('Shortage Qty'),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
