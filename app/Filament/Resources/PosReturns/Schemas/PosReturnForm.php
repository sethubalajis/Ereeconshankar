<?php

namespace App\Filament\Resources\PosReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PosReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('pos_sale_id')->relationship('posSale', 'invoice_number')
                    ->required(),
                DatePicker::make('return_date')
                    ->required(),
                TextInput::make('return_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('reason')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
