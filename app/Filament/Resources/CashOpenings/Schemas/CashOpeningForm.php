<?php

namespace App\Filament\Resources\CashOpenings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashOpeningForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateOpeningAmount = function ($state, callable $set) {
            if (!$state) return;
            
            $currentRegister = \App\Models\CashRegister::find($state);
            if ($currentRegister) {
                $previousRegister = \App\Models\CashRegister::where('pos_terminal_id', $currentRegister->pos_terminal_id)
                    ->where('id', '<', $currentRegister->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($previousRegister) {
                    $lastClosing = \App\Models\CashClosing::where('cash_register_id', $previousRegister->id)
                        ->orderBy('id', 'desc')
                        ->first();
                        
                    if ($lastClosing) {
                        $set('opening_amount', (float) $lastClosing->closing_amount);
                    } else {
                        $set('opening_amount', (float) $previousRegister->closing_balance);
                    }
                }
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('cash_register_id')
                    ->relationship('cashRegister', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name)
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateOpeningAmount),
                DateTimePicker::make('opening_time')
                    ->required(),
                TextInput::make('opening_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
            ]);
    }
}
