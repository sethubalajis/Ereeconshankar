<?php

namespace App\Filament\Resources\PickingLists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PickingListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name')
                    ->required(),
                \Filament\Forms\Components\TextInput::make('reference_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('reference_type')
                    ->options([
                        'App\Models\SalesOrder' => 'Sales Order',
                        'App\Models\WarehouseTransfer' => 'Warehouse Transfer',
                        'App\Models\PosSale' => 'POS Sale',
                    ])
                    ->searchable(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'picked' => 'Picked',
                        'packed' => 'Packed',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
