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
                    ->required(),
                TextInput::make('discount_percentage')
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->numeric(),
                DatePicker::make('valid_from'),
                DatePicker::make('valid_to'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
