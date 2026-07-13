<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTaxDeclarationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Investment Types
        $investmentTypes = [
            ['name' => 'Life Insurance (LIC)', 'section' => '80C', 'max_limit' => 150000],
            ['name' => 'Public Provident Fund (PPF)', 'section' => '80C', 'max_limit' => 150000],
            ['name' => 'ELSS Mutual Fund', 'section' => '80C', 'max_limit' => 150000],
            ['name' => 'Home Loan Principal', 'section' => '80C', 'max_limit' => 150000],
            ['name' => 'Health Insurance (Self/Family)', 'section' => '80D', 'max_limit' => 25000],
            ['name' => 'Health Insurance (Parents)', 'section' => '80D', 'max_limit' => 50000],
            ['name' => 'National Pension System (NPS)', 'section' => '80CCD(1B)', 'max_limit' => 50000],
            ['name' => 'Home Loan Interest', 'section' => '24(b)', 'max_limit' => 200000],
        ];

        foreach ($investmentTypes as $type) {
            \App\Models\InvestmentType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'section' => $type['section'],
                    'max_limit' => $type['max_limit'],
                    'status' => true,
                ]
            );
        }

        // 2. Find Employee "Rajesh"
        $employee = \App\Models\Employee::where('first_name', 'like', '%rajesh%')
            ->orWhere('last_name', 'like', '%rajesh%')
            ->first();

        if (!$employee) {
            $this->command->warn('Employee Rajesh not found. Creating a dummy Rajesh.');
            $employee = \App\Models\Employee::create([
                'employee_code' => 'EMP' . rand(1000, 9999),
                'first_name' => 'Rajesh',
                'last_name' => 'Kumar',
                'joining_date' => now()->subYears(2),
                'status' => 'active',
            ]);
        }

        // 3. Find or Create Financial Year
        $financialYear = \App\Models\FinancialYear::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$financialYear) {
            $financialYear = \App\Models\FinancialYear::firstOrCreate([
                'name' => '2026-2027',
            ], [
                'start_date' => '2026-04-01',
                'end_date' => '2027-03-31',
                'status' => 'active',
            ]);
        }

        // 4. Create Employee Tax Declaration
        $declaration = \App\Models\EmployeeTaxDeclaration::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'financial_year_id' => $financialYear->id,
            ],
            [
                'company_id' => $employee->company_id ?? (\App\Models\Company::first()->id ?? null),
                'branch_id' => $employee->branch_id ?? (\App\Models\Branch::first()->id ?? null),
                'tax_regime' => 'old',
                'declaration_date' => now(),
                'status' => 'approved',
                'approved_date' => now(),
                'remarks' => 'Approved by HR',
            ]
        );

        // 5. Create Declaration Items
        $lic = \App\Models\InvestmentType::where('name', 'Life Insurance (LIC)')->first();
        $health = \App\Models\InvestmentType::where('name', 'Health Insurance (Self/Family)')->first();
        $ppf = \App\Models\InvestmentType::where('name', 'Public Provident Fund (PPF)')->first();
        $homeLoanInterest = \App\Models\InvestmentType::where('name', 'Home Loan Interest')->first();

        $items = [
            [
                'investment_type_id' => $lic->id,
                'section' => $lic->section,
                'declared_amount' => 45000,
                'approved_amount' => 45000,
                'approval_status' => 'approved',
                'remarks' => 'LIC premium receipt verified.',
            ],
            [
                'investment_type_id' => $health->id,
                'section' => $health->section,
                'declared_amount' => 20000,
                'approved_amount' => 20000,
                'approval_status' => 'approved',
                'remarks' => 'Health insurance policy verified.',
            ],
            [
                'investment_type_id' => $ppf->id,
                'section' => $ppf->section,
                'declared_amount' => 150000,
                'approved_amount' => 105000, // Partial approval because 80C total limit is 1.5L and LIC is already 45k
                'approval_status' => 'approved',
                'remarks' => 'Capped at 80C maximum limit of 1.5 Lakhs combined with LIC.',
            ],
            [
                'investment_type_id' => $homeLoanInterest->id,
                'section' => $homeLoanInterest->section,
                'declared_amount' => 180000,
                'approved_amount' => 180000,
                'approval_status' => 'approved',
                'remarks' => 'Home loan interest certificate verified.',
            ]
        ];

        foreach ($items as $item) {
            \App\Models\EmployeeTaxDeclarationItem::updateOrCreate(
                [
                    'employee_tax_declaration_id' => $declaration->id,
                    'investment_type_id' => $item['investment_type_id'],
                ],
                $item
            );
        }

        $this->command->info('Successfully seeded Investment Types and Tax Declarations for employee Rajesh!');
    }
}
