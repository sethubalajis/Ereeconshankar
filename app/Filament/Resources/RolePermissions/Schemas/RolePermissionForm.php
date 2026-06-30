<?php

namespace App\Filament\Resources\RolePermissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RolePermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('role_id')->relationship('role', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('permission_id')->relationship('permission', 'name')
                    ->required(),
            ]);
    }
}
