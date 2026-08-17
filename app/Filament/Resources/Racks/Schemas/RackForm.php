<?php

namespace App\Filament\Resources\Racks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live(),
                \Filament\Forms\Components\Select::make('warehouse_zone_id')
                    ->relationship('warehouseZone', 'name', function ($query, $get) {
                        $branchId = $get('branch_id');
                        if ($branchId) {
                            $query->where('branch_id', $branchId);
                        }
                    })
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto generated on creation'),
            ]);
    }
}
