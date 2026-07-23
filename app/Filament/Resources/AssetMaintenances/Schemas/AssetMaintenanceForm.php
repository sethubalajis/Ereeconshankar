<?php

namespace App\Filament\Resources\AssetMaintenances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssetMaintenanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('asset_id')->relationship('asset', 'name')
                    ->required(),
                DatePicker::make('maintenance_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
