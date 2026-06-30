<?php

namespace App\Filament\Resources\GstRates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GstRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('cgst_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('sgst_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('igst_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('cess_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
