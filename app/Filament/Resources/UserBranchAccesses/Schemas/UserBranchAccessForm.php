<?php

namespace App\Filament\Resources\UserBranchAccesses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserBranchAccessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}
