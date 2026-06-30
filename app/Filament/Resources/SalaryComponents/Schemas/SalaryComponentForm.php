<?php

namespace App\Filament\Resources\SalaryComponents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SalaryComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                Toggle::make('is_taxable')
                    ->required(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
