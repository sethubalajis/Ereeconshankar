<?php

use App\Filament\Resources\KpiReports\Pages\CreateKpiReport;
use App\Filament\Resources\KpiReports\Pages\EditKpiReport;
use App\Filament\Resources\KpiReports\Pages\ListKpiReports;
use App\Models\KpiReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeKpiReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from kpi reports list', function (): void { get('/admin/kpi-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create kpi report page', function (): void { get('/admin/kpi-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a kpi report', function (): void {
    actingAs(makeKpiReportsAdminUser());

    Livewire::test(CreateKpiReport::class)
        ->fillForm(['kpi_name' => '', 'from_date' => null, 'to_date' => null, 'value' => null])
        ->call('create')
        ->assertHasFormErrors(['kpi_name' => 'required', 'from_date' => 'required', 'to_date' => 'required', 'value' => 'required']);
});

it('performs kpi report crud operations', function (): void {
    actingAs(makeKpiReportsAdminUser());

    Livewire::test(CreateKpiReport::class)
        ->fillForm(['kpi_name' => 'Revenue Per Employee', 'from_date' => '2026-07-01', 'to_date' => '2026-07-31', 'report_date' => '2026-07-31', 'value' => 95.5])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = KpiReport::query()->firstOrFail();
    expect($record->from_date->format('Y-m-d'))->toBe('2026-07-01');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-31');

    Livewire::test(ListKpiReports::class)->assertSuccessful()->assertSee('Revenue Per Employee');

    Livewire::test(EditKpiReport::class, ['record' => $record->getKey()])
        ->fillForm(['kpi_name' => 'Cost Per Employee', 'from_date' => '2026-07-01', 'to_date' => '2026-08-31', 'report_date' => '2026-08-31', 'value' => 90])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->kpi_name)->toBe('Cost Per Employee');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-08-31');

    Livewire::test(EditKpiReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(KpiReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('downloads detailed kpi report pdf successfully', function (): void {
    actingAs(makeKpiReportsAdminUser());

    $record = KpiReport::create([
        'kpi_name' => 'Revenue Per Employee',
        'from_date' => '2026-07-01',
        'to_date' => '2026-07-31',
        'value' => 1500.00,
    ]);

    Livewire::test(ListKpiReports::class)
        ->callTableAction('download_pdf', $record)
        ->assertFileDownloaded('kpi-report-' . $record->id . '.pdf');
});


