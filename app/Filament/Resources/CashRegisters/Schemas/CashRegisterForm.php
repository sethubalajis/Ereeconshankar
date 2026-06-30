<?php

namespace App\Filament\Resources\CashRegisters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashRegisterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('pos_terminal_id')->relationship('posTerminal', 'terminal_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required(),
                TextInput::make('opening_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('closing_balance')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
            ]);
    }
}
