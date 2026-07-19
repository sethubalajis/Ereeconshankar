<?php

namespace App\Filament\Resources\PromoCodes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromoCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->default(fn () => 'PC-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('discount_percentage')
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->numeric(),
                DatePicker::make('valid_from')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('valid_to')
                    ->default(now()->timezone('Asia/Kolkata')),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
