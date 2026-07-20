<?php

namespace App\Filament\Resources\EmployeeLeaves\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeLeaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('leave_type_id')->relationship('leaveType', 'name')
                    ->required(),
                DatePicker::make('start_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                DatePicker::make('end_date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('reason')
                    ->columnSpanFull(),
            ]);
    }
}
