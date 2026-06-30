<?php

namespace App\Filament\Resources\MembershipPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MembershipPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('duration_months')
                    ->required()
                    ->numeric()
                    ->default(12),
                Textarea::make('benefits_description')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
