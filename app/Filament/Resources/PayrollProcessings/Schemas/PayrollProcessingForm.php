<?php

namespace App\Filament\Resources\PayrollProcessings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayrollProcessingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('payroll_cycle_id')->relationship('payrollCycle', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                TextInput::make('gross_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total_deductions')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('net_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('status')
                    ->required()
                    ->default('processed'),
            ]);
    }
}
