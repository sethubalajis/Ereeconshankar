<?php

namespace App\Filament\Branch\Resources\EmployeeSalaryAssignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeSalaryAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('salary_structure_id')->relationship('salaryStructure', 'name')
                    ->required(),
                TextInput::make('gross_salary')
                    ->required()
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.0),
                DatePicker::make('effective_from')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
            ]);
    }
}
