<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('user_id')->relationship('user', 'name'),
                \Filament\Forms\Components\Select::make('department_id')->relationship('department', 'name'),
                \Filament\Forms\Components\Select::make('designation_id')->relationship('designation', 'name'),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name'),
                TextInput::make('employee_code')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name'),
                DatePicker::make('dob'),
                DatePicker::make('joining_date'),
                TextInput::make('pan_number'),
                TextInput::make('pf_number'),
                \Filament\Forms\Components\Toggle::make('pf_opt_in')
                    ->label('Opt-in for PF')
                    ->default(true),
                TextInput::make('esi_number'),
                \Filament\Forms\Components\Toggle::make('esi_opt_in')
                    ->label('Opt-in for ESI')
                    ->default(true),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
