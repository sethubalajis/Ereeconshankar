<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_group_id')->relationship('customerGroup', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Textarea::make('address')
                    ->columnSpanFull(),
                DatePicker::make('dob')
                    ->default(now()->timezone('Asia/Kolkata')),
                DatePicker::make('anniversary')
                    ->default(now()->timezone('Asia/Kolkata')),
                TextInput::make('gst_number'),
                TextInput::make('total_points')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('wallet_balance')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('₹'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
