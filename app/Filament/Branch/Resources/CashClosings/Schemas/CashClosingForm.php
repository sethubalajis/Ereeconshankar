<?php

namespace App\Filament\Branch\Resources\CashClosings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashClosingForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateClosingAmount = function ($state, callable $set) {
            if (!$state) return;
            
            $register = \App\Models\CashRegister::find($state);
            if ($register) {
                $openingAmount = \App\Models\CashOpening::where('cash_register_id', $register->id)->sum('opening_amount');
                $salesAmount = \App\Models\PosSale::where('pos_terminal_id', $register->pos_terminal_id)
                    ->where('sale_date', $register->date)
                    ->sum('net_amount');
                
                $set('closing_amount', (float) ($openingAmount + $salesAmount));
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('cash_register_id')
                    ->label('Cash Register')
                    ->options(fn () => \App\Models\CashRegister::with('posTerminal')->get()->pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateClosingAmount),
                DateTimePicker::make('closing_time')
                    ->required()
                    ->timezone('Asia/Kolkata')
                    ->default(now()),
                TextInput::make('closing_amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('variance')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
            ]);
    }
}
