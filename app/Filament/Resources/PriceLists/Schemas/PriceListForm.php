<?php

namespace App\Filament\Resources\PriceLists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PriceListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'PL-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                DatePicker::make('valid_from')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('valid_to')
                    ->default(now()->timezone('Asia/Kolkata')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
