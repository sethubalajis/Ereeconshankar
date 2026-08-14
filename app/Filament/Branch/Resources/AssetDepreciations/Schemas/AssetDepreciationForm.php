<?php

namespace App\Filament\Branch\Resources\AssetDepreciations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AssetDepreciationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('asset_id')->relationship('asset', 'name')
                    ->required(),
                DatePicker::make('depreciation_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('current_value')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
            ]);
    }
}
