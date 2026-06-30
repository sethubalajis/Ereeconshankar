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
                \Filament\Forms\Components\Select::make('warehouse_zone_id')->relationship('warehouseZone', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
            ]);
    }
}
