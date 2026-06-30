<?php

namespace App\Filament\Resources\PayrollCycles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollCycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                DatePicker::make('payment_date'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
            ]);
    }
}
