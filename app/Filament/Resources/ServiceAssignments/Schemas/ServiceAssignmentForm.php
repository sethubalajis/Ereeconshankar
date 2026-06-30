<?php

namespace App\Filament\Resources\ServiceAssignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('service_request_id')->relationship('serviceRequest', 'request_number')
                    ->required(),
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                DatePicker::make('assigned_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('assigned'),
            ]);
    }
}
