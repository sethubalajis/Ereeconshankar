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
                TextInput::make('document_type')
                    ->required(),
                TextInput::make('document_path')
                    ->required(),
                Toggle::make('is_verified')
                    ->required(),
            ]);
    }
}
