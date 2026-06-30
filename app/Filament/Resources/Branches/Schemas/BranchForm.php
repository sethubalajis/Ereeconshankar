<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('company_id')->relationship('company', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('type'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                Textarea::make('address')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('city_id')->relationship('city', 'name'),
                \Filament\Forms\Components\Select::make('state_id')->relationship('state', 'name'),
                \Filament\Forms\Components\Select::make('country_id')->relationship('country', 'name'),
                TextInput::make('pin_code'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
