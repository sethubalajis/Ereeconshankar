<?php

namespace App\Filament\Branch\Resources\EmployeeExits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeExitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                DatePicker::make('resignation_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                DatePicker::make('last_working_day')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString()),
                \Filament\Forms\Components\Select::make('exit_type')
                    ->options([
                        'resignation' => 'Resignation',
                        'termination' => 'Termination',
                        'retirement' => 'Retirement',
                        'contract_end' => 'Contract End',
                    ]),
                Textarea::make('reason')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
            ]);
    }
}
