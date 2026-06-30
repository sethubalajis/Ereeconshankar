<?php

namespace App\Filament\Resources\BranchSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BranchSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('setting_key')
                    ->required(),
                Textarea::make('setting_value')
                    ->columnSpanFull(),
            ]);
    }
}
