<?php

namespace App\Filament\Resources\Bins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BinForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('rack_id')->relationship('rack', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('max_weight')
                    ->numeric(),
            ]);
    }
}
