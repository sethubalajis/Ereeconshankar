<?php

namespace App\Filament\Resources\EsiContributions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EsiContributionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                TextInput::make('employee_share')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('employer_share')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('month')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
            ]);
    }
}
