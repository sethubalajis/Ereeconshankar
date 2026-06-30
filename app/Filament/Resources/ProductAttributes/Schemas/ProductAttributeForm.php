<?php

namespace App\Filament\Resources\ProductAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('text'),
            ]);
    }
}
