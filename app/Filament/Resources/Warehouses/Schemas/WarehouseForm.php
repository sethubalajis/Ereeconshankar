<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->placeholder('Leave empty to auto-generate')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('capacity')
                    ->numeric(),
                Toggle::make('is_default')
                    ->label('Is Default')
                    ->default(false),
                Toggle::make('is_damaged')
                    ->label('Is Damaged / Quarantine Warehouse')
                    ->default(false),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
