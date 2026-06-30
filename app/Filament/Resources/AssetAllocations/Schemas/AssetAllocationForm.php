<?php

namespace App\Filament\Resources\AssetAllocations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetAllocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('asset_id')->relationship('asset', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                DatePicker::make('allocation_date')
                    ->required(),
                DatePicker::make('return_date'),
                TextInput::make('status')
                    ->required()
                    ->default('allocated'),
            ]);
    }
}
