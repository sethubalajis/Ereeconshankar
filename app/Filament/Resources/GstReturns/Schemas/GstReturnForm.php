<?php

namespace App\Filament\Resources\GstReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GstReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('return_type')
                    ->required(),
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                DatePicker::make('filing_date'),
                TextInput::make('arn_number'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
