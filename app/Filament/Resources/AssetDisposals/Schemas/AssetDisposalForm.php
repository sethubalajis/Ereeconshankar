<?php

namespace App\Filament\Resources\AssetDisposals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssetDisposalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('asset_id')->relationship('asset', 'name')
                    ->required(),
                DatePicker::make('disposal_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                TextInput::make('sale_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('reason')
                    ->columnSpanFull(),
            ]);
    }
}
