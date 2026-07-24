<?php

namespace App\Filament\Resources\TaxGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaxGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('total_tax_percentage')
                    ->required()
                    ->numeric()
                    ->suffix('%')
                    ->default(0.0),
            ]);
    }
}
