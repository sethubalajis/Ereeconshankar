<?php

namespace App\Filament\Resources\FestivalOffers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FestivalOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('discount_percentage')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(0.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
