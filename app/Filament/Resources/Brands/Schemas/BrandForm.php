<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('code')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Auto generated on creation'),
                FileUpload::make('logo')
                ->disk("public")
                
                    ->image(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
