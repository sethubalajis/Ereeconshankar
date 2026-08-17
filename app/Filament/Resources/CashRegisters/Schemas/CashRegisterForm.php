<?php

namespace App\Filament\Resources\CashRegisters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashRegisterForm
{
    public static function configure(Schema $schema): Schema
    {
        $updateBalances = function ($set, $get) {
            $terminalId = $get('pos_terminal_id');
            $userId = $get('user_id');
            $date = $get('date');

            if ($terminalId && $userId && $date) {
                $register = \App\Models\CashRegister::where('pos_terminal_id', $terminalId)
                    ->where('user_id', $userId)
                    ->where('date', $date)
                    ->first();
                
                if ($register) {
                    $openingAmount = \App\Models\CashOpening::where('cash_register_id', $register->id)->sum('opening_amount');
                    $closingAmount = \App\Models\CashClosing::where('cash_register_id', $register->id)->sum('closing_amount');
                    
                    $set('opening_balance', $openingAmount);
                    $set('closing_balance', $closingAmount);
                } else {
                    $set('opening_balance', 0.0);
                    $set('closing_balance', 0.0);
                }
            }
        };

        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required()
                    ->live(),
                \Filament\Forms\Components\Select::make('pos_terminal_id')
                    ->relationship('posTerminal', 'terminal_name', function ($query, $get) {
                        $branchId = $get('branch_id');
                        if ($branchId) {
                            $query->where('branch_id', $branchId);
                        }
                    })
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateBalances),
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name')
                    ->required()
                    ->live()
                    ->afterStateUpdated($updateBalances),
                \Filament\Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->live()
                    ->afterStateUpdated($updateBalances),
                TextInput::make('opening_balance')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                TextInput::make('closing_balance')
                    ->numeric()
                    ->prefix('₹'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                    ])
                    ->required()
                    ->default('open'),
            ]);
    }
}
