<?php

namespace App\Filament\Resources\EmployeeDocuments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('employee_id')->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('document_type')
                    ->options([
                        'Identity Proof' => 'Identity Proof',
                        'Address Proof' => 'Address Proof',
                        'Educational Certificate' => 'Educational Certificate',
                        'Experience Letter' => 'Experience Letter',
                        'Relieving Letter' => 'Relieving Letter',
                        'Salary Slip' => 'Salary Slip',
                        'Offer Letter' => 'Offer Letter',
                        'Appointment Letter' => 'Appointment Letter',
                        'Other' => 'Other',
                    ])
                    ->required(),
                \Filament\Forms\Components\FileUpload::make('document_path')
                    ->directory('employee-documents')
                    ->required(),
                Toggle::make('is_verified')
                    ->required(),
            ]);
    }
}
