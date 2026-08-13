<?php

namespace App\Filament\Branch\Resources\CustomerGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'CG-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('discount_percentage')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->suffix('%'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
