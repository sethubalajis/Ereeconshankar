<?php

namespace App\Filament\Resources\VendorPerformances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VendorPerformanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                DatePicker::make('review_date')
                    ->required(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('delivery_score')
                    ->numeric(),
                TextInput::make('quality_score')
                    ->numeric(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
