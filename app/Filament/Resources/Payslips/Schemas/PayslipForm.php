<?php

namespace App\Filament\Resources\Payslips\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayslipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('payroll_processing_id')->relationship('payrollProcessing', 'status')
                    ->required(),
                TextInput::make('payslip_number')
                    ->required(),
                TextInput::make('document_path'),
            ]);
    }
}
