<?php

namespace App\Filament\Branch\Resources\LoyaltyPrograms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LoyaltyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'LP-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('points_per_amount')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('amount_per_point')
                    ->required()
                    ->numeric()
                    ->default(100.0)
                    ->prefix('₹'),
                TextInput::make('minimum_points_to_redeem')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('value_per_point')
                    ->required()
                    ->numeric()
                    ->default(1.0)
                    ->prefix('₹'),
                TextInput::make('max_redemption_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->nullable()
                    ->maxValue(100),
                TextInput::make('points_expiry_months')
                    ->numeric()
                    ->nullable(),
                \Filament\Forms\Components\DatePicker::make('effective_from')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->nullable(),
                \Filament\Forms\Components\DatePicker::make('effective_to')->nullable(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
