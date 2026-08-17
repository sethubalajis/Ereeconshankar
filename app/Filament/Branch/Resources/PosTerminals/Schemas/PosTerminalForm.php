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
                    ->required()
                    ->default(fn () => 'TERM-' . str_pad((string) ((\App\Models\PosTerminal::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT))
                    ->unique(ignoreRecord: true),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
