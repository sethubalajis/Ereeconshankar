<?php

namespace App\Filament\Resources\TcsRecords\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TcsRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('section_code')
                    ->required(),
                TextInput::make('tcs_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                \Filament\Forms\Components\TextInput::make('collectee_id')->numeric()
                    ->required(),
                TextInput::make('collectee_type')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
