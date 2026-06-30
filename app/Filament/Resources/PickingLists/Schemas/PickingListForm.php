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
                \Filament\Forms\Components\TextInput::make('reference_id')->numeric()
                    ,
                TextInput::make('reference_type'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
