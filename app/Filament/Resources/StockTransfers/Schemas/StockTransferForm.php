<?php

namespace App\Filament\Resources\StockTransfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('transfer_request_id')->relationship('transferRequest', 'status'),
                \Filament\Forms\Components\Select::make('from_branch_id')->relationship('fromBranch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('to_branch_id')->relationship('toBranch', 'name')
                    ->required(),
                DatePicker::make('transfer_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('vehicle_number'),
                TextInput::make('driver_name'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'in_transit' => 'In Transit',
                        'received' => 'Received',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('in_transit'),
            ]);
    }
}
