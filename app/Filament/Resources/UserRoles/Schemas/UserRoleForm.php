<?php

namespace App\Filament\Resources\UserRoles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('role_id')->relationship('role', 'name')
                    ->required(),
            ]);
    }
}
