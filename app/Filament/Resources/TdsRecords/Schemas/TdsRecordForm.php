<?php

namespace App\Filament\Resources\TdsRecords\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TdsRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('section_code')
                    ->required(),
                TextInput::make('tds_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\TextInput::make('deductee_id')->numeric()
                    ->required(),
                TextInput::make('deductee_type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
