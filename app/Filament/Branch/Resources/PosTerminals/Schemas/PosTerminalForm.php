<?php

namespace App\Filament\Branch\Resources\PosTerminals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PosTerminalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('terminal_name')
                    ->required(),
                TextInput::make('terminal_code')
                    ->required(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
