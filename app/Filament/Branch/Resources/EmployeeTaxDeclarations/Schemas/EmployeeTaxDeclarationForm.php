<?php

namespace App\Filament\Branch\Resources\EmployeeTaxDeclarations\Schemas;

use Filament\Schemas\Schema;

class EmployeeTaxDeclarationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name'),
                \Filament\Forms\Components\Select::make('branch_id')
                    ->relationship('branch', 'name'),
                \Filament\Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->required(),
                \Filament\Forms\Components\Select::make('financial_year_id')
                    ->relationship('financialYear', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('tax_regime')
                    ->options([
                        'old' => 'Old Regime',
                        'new' => 'New Regime',
                    ])
                    ->required()
                    ->default('old'),
                \Filament\Forms\Components\DatePicker::make('declaration_date')
                    ->required()
                    ->default(now()),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('draft'),
                \Filament\Forms\Components\Select::make('submitted_by')
                    ->relationship('submittedBy', 'name'),
                \Filament\Forms\Components\Select::make('approved_by')
                    ->relationship('approvedBy', 'name'),
                \Filament\Forms\Components\DatePicker::make('approved_date'),
                \Filament\Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
                
                \Filament\Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        \Filament\Forms\Components\Select::make('investment_type_id')
                            ->relationship('investmentType', 'name')
                            ->required(),
                        \Filament\Forms\Components\TextInput::make('section')
                            ->maxLength(255),
                        \Filament\Forms\Components\TextInput::make('declared_amount')
                            ->required()
                            ->numeric()
                            ->default(0.00),
                        \Filament\Forms\Components\TextInput::make('approved_amount')
                            ->numeric()
                            ->default(0.00),
                        \Filament\Forms\Components\FileUpload::make('proof_document'),
                        \Filament\Forms\Components\Select::make('approval_status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                        \Filament\Forms\Components\TextInput::make('remarks')
                            ->maxLength(255),
                    ])
                    ->columnSpanFull()
            ]);
    }
}
