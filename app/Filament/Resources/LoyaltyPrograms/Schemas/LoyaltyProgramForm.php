<?php

namespace App\Filament\Resources\LoyaltyPrograms\Schemas;

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
                    ->required(),
                \Filament\Forms\Components\Select::make('customer_group_id')
                    ->relationship('customerGroup', 'name')
                    ->nullable(),
                \Filament\Forms\Components\Select::make('membership_plan_id')
                    ->relationship('membershipPlan', 'name')
                    ->nullable(),
                TextInput::make('points_per_amount')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('amount_per_point')
                    ->required()
                    ->numeric()
                    ->default(100.0),
                TextInput::make('minimum_points_to_redeem')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('value_per_point')
                    ->required()
                    ->numeric()
                    ->default(1.0),
                TextInput::make('max_redemption_percentage')
                    ->numeric()
                    ->nullable()
                    ->maxValue(100),
                TextInput::make('points_expiry_months')
                    ->numeric()
                    ->nullable(),
                \Filament\Forms\Components\DatePicker::make('effective_from')->nullable(),
                \Filament\Forms\Components\DatePicker::make('effective_to')->nullable(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
