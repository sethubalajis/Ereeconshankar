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
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($set, $state) {
                        if (!$state) {
                            $set('gross_pay', 0.0);
                            $set('total_deductions', 0.0);
                            $set('net_pay', 0.0);
                            return;
                        }
                        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $state)->orderBy('effective_from', 'desc')->first();
                        if ($assignment) {
                            $set('gross_pay', $assignment->gross_salary);
                            $set('total_deductions', 0.0);
                            $set('net_pay', $assignment->gross_salary);
                        }
                    }),
                TextInput::make('gross_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $get) {
                        $set('net_pay', (float) $get('gross_pay') - (float) $get('total_deductions'));
                    }),
                TextInput::make('total_deductions')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($set, $get) {
                        $set('net_pay', (float) $get('gross_pay') - (float) $get('total_deductions'));
                    }),
                \Filament\Forms\Components\KeyValue::make('allowances')
                    ->keyLabel('Allowance Type')
                    ->valueLabel('Amount')
                    ->columnSpanFull(),
                \Filament\Forms\Components\KeyValue::make('deductions')
                    ->keyLabel('Deduction Type')
                    ->valueLabel('Amount')
                    ->columnSpanFull(),
                TextInput::make('net_pay')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->readonly(),
                TextInput::make('status')
                    ->required()
                    ->default('processed'),
            ]);
    }
}
