<?php

namespace App\Filament\Resources\PriceLists\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PriceListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->nullable(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'PL-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                \Filament\Forms\Components\Select::make('membership_plan_id')
                    ->relationship('membershipPlan', 'name')
                    ->nullable(),
                \Filament\Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->nullable(),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }
}
