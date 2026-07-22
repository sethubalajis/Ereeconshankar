<?php

namespace App\Filament\Branch\Resources\PosCounters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PosCounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('counter_name')
                    ->required(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
