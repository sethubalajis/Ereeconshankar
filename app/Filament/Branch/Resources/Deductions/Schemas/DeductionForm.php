<?php

namespace App\Filament\Branch\Resources\Deductions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeductionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('deduction_type')
                    ->options([
                        'tax' => 'Tax',
                        'loan_repayment' => 'Loan Repayment',
                        'unpaid_leave' => 'Unpaid Leave',
                        'other' => 'Other',
                    ])
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                DatePicker::make('month')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
            ]);
    }
}
