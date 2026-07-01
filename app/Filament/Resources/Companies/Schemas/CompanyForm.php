<?php

namespace App\Filament\Resources\Companies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('gst_number'),
                TextInput::make('pan_number'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('website')
                    ->url(),
                FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->directory('company-logos'),
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
