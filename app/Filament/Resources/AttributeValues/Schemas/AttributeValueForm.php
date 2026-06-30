<?php

namespace App\Filament\Resources\AttributeValues\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttributeValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_attribute_id')->relationship('productAttribute', 'name')
                    ->required(),
                TextInput::make('value')
                    ->required(),
            ]);
    }
}
