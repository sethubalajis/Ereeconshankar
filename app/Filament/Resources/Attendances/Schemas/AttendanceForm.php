<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                DatePicker::make('date')
                    ->default(now()->timezone('Asia/Kolkata')->toDateString())
                    ->required(),
                TimePicker::make('check_in')
                    ->default(now()->timezone('Asia/Kolkata')->toTimeString()),
                TimePicker::make('check_out')
                    ->default(now()->timezone('Asia/Kolkata')->toTimeString()),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'half_day' => 'Half Day',
                        'leave' => 'Leave',
                    ])
                    ->required()
                    ->default('present'),
            ]);
    }
}
