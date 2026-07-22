<?php

namespace App\Filament\Branch\Resources\EmployeeTransfers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeTransferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('from_branch_id')->relationship('fromBranch', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('to_branch_id')->relationship('toBranch', 'name')
                    ->required(),
                DatePicker::make('transfer_date')
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
            ]);
    }
}
