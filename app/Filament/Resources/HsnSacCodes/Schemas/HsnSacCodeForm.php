<?php

namespace App\Filament\Resources\HsnSacCodes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class HsnSacCodeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('type')
                    ->required()
                    ->default('HSN'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
