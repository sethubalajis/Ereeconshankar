<?php

namespace App\Filament\Resources\InventoryCounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('warehouse_id')->relationship('warehouse', 'name'),
                DatePicker::make('count_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
            ]);
    }
}
