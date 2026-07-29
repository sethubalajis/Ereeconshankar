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
                    ->default(fn () => 'MP-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('fee')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                TextInput::make('duration_months')
                    ->required()
                    ->numeric()
                    ->default(12),
                TextInput::make('upgrade_threshold')
                    ->label('Upgrade Threshold (Lifetime Purchases)')
                    ->numeric()
                    ->prefix('₹')
                    ->nullable(),
                Textarea::make('benefits_description')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
