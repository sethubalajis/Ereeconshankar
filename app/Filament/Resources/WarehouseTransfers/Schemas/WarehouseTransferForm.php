<?php

namespace App\Filament\Resources\WarehouseTransfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WarehouseTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('from_warehouse_id')->relationship('fromWarehouse', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('to_warehouse_id')->relationship('toWarehouse', 'name')
                    ->required(),
                DatePicker::make('transfer_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
