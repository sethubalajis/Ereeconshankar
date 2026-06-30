<?php

namespace App\Filament\Resources\AccountGroups\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AccountGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                \Filament\Forms\Components\Select::make('parent_id')->relationship('parent', 'name'),
                TextInput::make('type')
                    ->required(),
            ]);
    }
}
