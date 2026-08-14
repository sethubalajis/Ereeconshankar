<?php

namespace App\Filament\Branch\Resources\Coupons\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('discount_percentage')
                    ->numeric()
                    ->suffix('%'),
                TextInput::make('discount_amount')
                    ->numeric()
                    ->prefix('₹'),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('min_purchase_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('max_usage')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('current_usage')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
