<?php

namespace App\Filament\Resources\DiscountRules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('promotion_type_id')->relationship('promotionType', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->default(fn () => 'DR-' . strtoupper(\Illuminate\Support\Str::random(6)))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('discount_percentage')
                    ->numeric(),
                TextInput::make('discount_amount')
                    ->numeric(),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('min_purchase_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
