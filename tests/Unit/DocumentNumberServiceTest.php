<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Branch;
use App\Models\DocumentNumberConfiguration;
use App\Models\DocumentNumberSequence;
use App\Models\FinancialYear;
use App\Services\DocumentNumberService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class DocumentNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $company;
    protected $branch;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->company = Company::factory()->create();
        $this->branch = Branch::factory()->create(['company_id' => $this->company->id]);
        
        FinancialYear::factory()->create([
            'company_id' => $this->company->id,
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
        ]);
    }

    public function test_default_fallback_without_configuration()
    {
        $number = DocumentNumberService::generate('App\Models\PosSale', $this->company->id);
        
        // POS-000001
        $this->assertEquals('POS-000001', $number);
        
        $number2 = DocumentNumberService::generate('App\Models\PosSale', $this->company->id);
        $this->assertEquals('POS-000002', $number2);
    }

    public function test_company_specific_prefix_and_padding()
    {
        DocumentNumberConfiguration::create([
            'company_id' => $this->company->id,
            'document_type' => 'App\Models\PosSale',
            'prefix' => 'INV',
            'separator' => '/',
            'number_padding' => 4,
            'starting_number' => 100,
            'include_financial_year' => false,
        ]);

        $number = DocumentNumberService::generate('App\Models\PosSale', $this->company->id);
        $this->assertEquals('INV/0100', $number);

        $number2 = DocumentNumberService::generate('App\Models\PosSale', $this->company->id);
        $this->assertEquals('INV/0101', $number2);
    }

    public function test_financial_year_based_code_with_reset()
    {
        DocumentNumberConfiguration::create([
            'company_id' => $this->company->id,
            'document_type' => 'App\Models\PosSale',
            'prefix' => 'TN/INV',
            'separator' => '/',
            'number_padding' => 6,
            'include_financial_year' => true,
            'financial_year_format' => 'YYYY-YY',
            'reset_frequency' => 'yearly',
        ]);

        // Mock date in 2026-27 FY
        $date = Carbon::parse('2026-08-20')->format('Y-m-d');
        $number = DocumentNumberService::generate('App\Models\PosSale', $this->company->id, null, $date);
        $this->assertEquals('TN/INV/2026-27/000001', $number);
    }
}
