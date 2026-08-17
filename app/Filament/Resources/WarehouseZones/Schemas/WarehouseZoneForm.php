<?php

namespace App\Filament\Resources\WarehouseZones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WarehouseZoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live(),
                \Filament\Forms\Components\Select::make('warehouse_id')
                    ->relationship('warehouse', 'name', function ($query, $get) {
                        $branchId = $get('branch_id');
                        if ($branchId) {
                            $query->where('branch_id', $branchId);
                        }
                    })
                    ->required(),
                \Filament\Forms\Components\Select::make('name')
                    ->options([
                        'Zone A' => 'Zone A',
                        'Zone B' => 'Zone B',
                        'Zone C' => 'Zone C',
                    ])
                    ->required(),
                TextInput::make('code')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto generated on creation'),
                \Filament\Forms\Components\Select::make('type')
                    ->options([
                        'Receiving' => 'Receiving',
                        'Storage' => 'Storage',
                        'Shipping' => 'Shipping',
                    ]),
            ]);
    }
}
