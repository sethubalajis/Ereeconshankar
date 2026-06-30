<?php

namespace App\Filament\Resources\BranchUsers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required(),
            ]);
    }
}
