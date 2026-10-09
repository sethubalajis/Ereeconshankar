<?php

namespace App\Filament\Resources\CashBooks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class CashBookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->default(now('Asia/Kolkata'))
                    ->required(),
                TextInput::make('description'),
                TextInput::make('receipt_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $receipt = (float) ($get('receipt_amount') ?? 0);
                        $payment = (float) ($get('payment_amount') ?? 0);
                        $set('balance', round($receipt - $payment, 2));
                    }),
                TextInput::make('payment_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $receipt = (float) ($get('receipt_amount') ?? 0);
                        $payment = (float) ($get('payment_amount') ?? 0);
                        $set('balance', round($receipt - $payment, 2));
                    }),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0)
                    ->extraInputAttributes(['class' => 'font-bold']),
            ]);
    }
}