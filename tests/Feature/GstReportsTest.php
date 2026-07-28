<?php

use App\Filament\Resources\GstReports\Pages\CreateGstReport;
use App\Filament\Resources\GstReports\Pages\EditGstReport;
use App\Filament\Resources\GstReports\Pages\ListGstReports;
use App\Models\GstReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGstReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from gst reports list', function (): void { get('/admin/gst-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create gst report page', function (): void { get('/admin/gst-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a gst report', function (): void {
    actingAs(makeGstReportsAdminUser());
    Livewire::test(CreateGstReport::class)
        ->fillForm(['report_type' => '', 'period_start' => null, 'period_end' => null, 'summary_data' => ''])
        ->call('create')
        ->assertHasFormErrors(['report_type' => 'required', 'period_start' => 'required', 'period_end' => 'required']);
});

it('performs gst report crud operations', function (): void {
    actingAs(makeGstReportsAdminUser());

    Livewire::test(CreateGstReport::class)
        ->fillForm(['report_type' => 'GSTR-1', 'period_start' => '2026-07-01', 'period_end' => '2026-07-31', 'summary_data' => 'Initial'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = GstReport::query()->firstOrFail();
    Livewire::test(ListGstReports::class)->assertSuccessful()->assertSee('GSTR-1');

    Livewire::test(EditGstReport::class, ['record' => $record->getKey()])
        ->fillForm(['report_type' => 'GSTR-3B', 'period_start' => '2026-08-01', 'period_end' => '2026-08-31', 'summary_data' => 'Updated'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditGstReport::class, ['record' => $record->getKey()])->callAction('delete');
});

it('computes gst details and downloads pdf report with full breakdown', function (): void {
    actingAs(makeGstReportsAdminUser());
    
    $company = \App\Models\Company::query()->create(['name' => 'GST Co', 'code' => 'GSTCO1', 'status' => true]);
    $branch = \App\Models\Branch::query()->create(['company_id' => $company->id, 'name' => 'GST Branch', 'code' => 'GSTBR1', 'status' => true]);
    $posTerminal = \App\Models\PosTerminal::query()->create(['branch_id' => $branch->id, 'terminal_name' => 'Term 1', 'terminal_code' => 'T1', 'status' => true]);

    \App\Models\PosSale::query()->create([
        'branch_id' => $branch->id,
        'pos_terminal_id' => $posTerminal->id,
        'invoice_number' => 'INV-GST-101',
        'sale_date' => '2026-07-15',
        'total_amount' => 11800.00,
        'tax_amount' => 1800.00,
        'discount_amount' => 0.00,
        'net_amount' => 11800.00,
        'status' => 'completed',
    ]);

    $data = \App\Helpers\ReportPdfHelper::getGstReportData('GSTR-1', '2026-07-01', '2026-07-31');
    expect($data['summary']['invoice_count'])->toBe(1);
    expect($data['summary']['tax_amount'])->toBe(1800.0);
    expect($data['summary']['taxable_amount'])->toBe(10000.0);

    $record = GstReport::query()->create([
        'report_type' => 'GSTR-1',
        'period_start' => '2026-07-01',
        'period_end' => '2026-07-31',
        'summary_data' => 'Test',
    ]);

    $pdfResponse = \App\Helpers\ReportPdfHelper::downloadGstReportPdf($record);
    expect($pdfResponse)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class);
});
