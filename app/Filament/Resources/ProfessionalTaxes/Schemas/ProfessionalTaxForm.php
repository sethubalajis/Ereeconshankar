<?php

namespace App\Filament\Resources\ProfessionalTaxes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProfessionalTaxForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('state_id')->relationship('state', 'name'),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                DatePicker::make('month')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
            ]);
    }
}
