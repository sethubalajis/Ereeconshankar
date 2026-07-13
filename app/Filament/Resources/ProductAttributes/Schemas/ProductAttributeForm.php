<?php

namespace App\Filament\Resources\ProductAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
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
                Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'select' => 'Select',
                        'color' => 'Color',
                        'number' => 'Number',
                        'boolean' => 'Boolean',
                        'date' => 'Date',
                    ])
                    ->searchable()
                    ->native(false)
                    ->required()
                    ->default('text'),
            ]);
    }
}
