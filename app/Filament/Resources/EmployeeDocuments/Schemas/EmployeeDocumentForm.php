<?php

namespace App\Filament\Resources\EmployeeDocuments\Schemas;

use App\Models\Employee;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn (Employee $record) => "{$record->employee_code} - {$record->first_name} {$record->last_name}")
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('document_type')
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
                FileUpload::make('document_path')
                    ->label('Upload Document / Photo')
                    ->directory('employee-documents')
                    ->disk('public')
                    ->openable()
                    ->downloadable()
                    ->required(),
                Toggle::make('is_verified')
                    ->label('Is Verified')
                    ->default(false)
                    ->required(),
            ]);
    }
}