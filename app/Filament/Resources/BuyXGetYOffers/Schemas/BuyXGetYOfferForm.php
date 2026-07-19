<?php

namespace App\Filament\Resources\BuyXGetYOffers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BuyXGetYOfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('buy_product_id')->relationship('buyProduct', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('get_product_id')->relationship('getProduct', 'name')
                    ->required(),
                TextInput::make('buy_quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('get_quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
