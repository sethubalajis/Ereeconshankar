<?php

namespace App\Filament\Resources\TdsRecords\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TdsRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('payroll_cycle_id')
                    ->label('Payroll Cycle')
                    ->options(fn () => \App\Models\PayrollCycle::query()->orderByDesc('start_date')->pluck('name', 'id')->all())
                    ->searchable()
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::updateCalculations($set, $get)),
                \Filament\Forms\Components\Hidden::make('payroll_processing_id'),
                \Filament\Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::updateCalculations($set, $get)),
                \Filament\Forms\Components\Select::make('financial_year_id')
                    ->relationship('financialYear', 'name')
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::updateCalculations($set, $get)),
                \Filament\Forms\Components\Select::make('tax_regime')
                    ->options([
                        'old' => 'Old Regime',
                        'new' => 'New Regime',
                    ])
                    ->default('old')
                    ->live()
                    ->afterStateUpdated(fn ($set, $get) => self::updateCalculations($set, $get)),
                \Filament\Forms\Components\TextInput::make('annual_gross_salary')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\TextInput::make('approved_tax_deductions')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\TextInput::make('taxable_income')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\TextInput::make('annual_income_tax')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\TextInput::make('monthly_tds')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\TextInput::make('remaining_tds')
                    ->numeric()
                    ->prefix('₹')
                    ->default(0.00),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processed' => 'Processed',
                        'filed' => 'Filed',
                    ])
                    ->default('processed'),
            ]);
    }

    protected static function updateCalculations($set, $get)
    {
        $payrollCycleId = $get('payroll_cycle_id');
        $payrollProcessingId = $get('payroll_processing_id');
        $employeeId = $get('employee_id');
        $financialYearId = $get('financial_year_id');
        
        if ($payrollCycleId && ! $financialYearId) {
            $cycle = \App\Models\PayrollCycle::find($payrollCycleId);

            if ($cycle) {
                $financialYear = \App\Models\FinancialYear::where('start_date', '<=', $cycle->start_date)
                    ->where('end_date', '>=', $cycle->start_date)
                    ->first();

                if ($financialYear) {
                    $financialYearId = $financialYear->id;
                    $set('financial_year_id', $financialYearId);
                }
            }
        }

        if (!$employeeId && $payrollProcessingId) {
            $payroll = \App\Models\PayrollProcessing::find($payrollProcessingId);
            if ($payroll) {
                $employeeId = $payroll->employee_id;
                $set('employee_id', $employeeId);

                if (! $payrollCycleId) {
                    $payrollCycleId = $payroll->payroll_cycle_id;
                    $set('payroll_cycle_id', $payrollCycleId);
                }
            }
        }

        if ($payrollCycleId && $employeeId) {
            $payroll = \App\Models\PayrollProcessing::where('payroll_cycle_id', $payrollCycleId)
                ->where('employee_id', $employeeId)
                ->latest('id')
                ->first();

            $set('payroll_processing_id', $payroll?->id);
        }

        if (!$employeeId) return;

        $annualGross = 0;
        $assignment = \App\Models\EmployeeSalaryAssignment::where('employee_id', $employeeId)
            ->orderBy('effective_from', 'desc')
            ->first();
            
        if ($assignment) {
            $annualGross = $assignment->gross_salary * 12;
        }
        
        $approvedDeductions = 0;
        if ($financialYearId) {
            $declaration = \App\Models\EmployeeTaxDeclaration::where('employee_id', $employeeId)
                ->where('financial_year_id', $financialYearId)
                ->where('status', 'approved')
                ->first();
                
            if ($declaration) {
                $set('tax_regime', $declaration->tax_regime);
                $approvedDeductions = $declaration->items()->sum('approved_amount');
            }
        }

        $taxableIncome = max(0, $annualGross - $approvedDeductions);
        
        $annualTax = 0;
        if ($taxableIncome > 250000) {
            if ($taxableIncome <= 500000) {
                $annualTax = ($taxableIncome - 250000) * 0.05;
            } elseif ($taxableIncome <= 1000000) {
                $annualTax = (250000 * 0.05) + (($taxableIncome - 500000) * 0.20);
            } else {
                $annualTax = (250000 * 0.05) + (500000 * 0.20) + (($taxableIncome - 1000000) * 0.30);
            }
        }

        $monthlyTds = round($annualTax / 12, 2);
        
        $set('annual_gross_salary', round($annualGross, 2));
        $set('approved_tax_deductions', round($approvedDeductions, 2));
        $set('taxable_income', round($taxableIncome, 2));
        $set('annual_income_tax', round($annualTax, 2));
        $set('monthly_tds', round($monthlyTds, 2));
        $set('remaining_tds', max(0, round($annualTax - $monthlyTds, 2)));

    }
}
