<?php

use App\Filament\Resources\HrReports\Pages\CreateHrReport;
use App\Filament\Resources\HrReports\Pages\EditHrReport;
use App\Filament\Resources\HrReports\Pages\ListHrReports;
use App\Models\HrReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeHrReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from hr reports list', function (): void { get('/admin/hr-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create hr report page', function (): void { get('/admin/hr-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an hr report', function (): void {
    actingAs(makeHrReportsAdminUser());
    Livewire::test(CreateHrReport::class)
        ->fillForm(['from_date' => null, 'to_date' => null, 'total_employees' => null, 'new_hires' => null, 'exits' => null])
        ->call('create')
        ->assertHasFormErrors(['from_date' => 'required', 'to_date' => 'required', 'total_employees' => 'required', 'new_hires' => 'required', 'exits' => 'required']);
});

it('performs hr report crud operations', function (): void {
    actingAs(makeHrReportsAdminUser());

    Livewire::test(CreateHrReport::class)
        ->fillForm(['from_date' => '2026-07-01', 'to_date' => '2026-07-31', 'report_month' => '2026-07-31', 'total_employees' => 120, 'new_hires' => 8, 'exits' => 2])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = HrReport::query()->firstOrFail();
    expect($record->from_date->format('Y-m-d'))->toBe('2026-07-01');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-31');

    Livewire::test(ListHrReports::class)->assertSuccessful()->assertSee('120');

    Livewire::test(EditHrReport::class, ['record' => $record->getKey()])
        ->fillForm(['from_date' => '2026-07-01', 'to_date' => '2026-08-31', 'report_month' => '2026-08-31', 'total_employees' => 125, 'new_hires' => 10, 'exits' => 5])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->to_date->format('Y-m-d'))->toBe('2026-08-31');

    Livewire::test(EditHrReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(HrReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('automatically updates total employees, new hires, and exits when date range is selected', function (): void {
    actingAs(makeHrReportsAdminUser());

    $emp1 = \App\Models\Employee::create([
        'employee_code' => 'EMP-TEST-001',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'joining_date' => '2026-07-05',
    ]);
    
    $emp2 = \App\Models\Employee::create([
        'employee_code' => 'EMP-TEST-002',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'joining_date' => '2026-07-15',
    ]);

    \App\Models\EmployeeExit::create([
        'employee_id' => $emp1->id,
        'resignation_date' => '2026-07-20',
        'last_working_day' => '2026-07-25',
        'exit_type' => 'Resigned',
        'reason' => 'Personal reasons',
    ]);

    Livewire::test(CreateHrReport::class)
        ->set('data.from_date', '2026-07-01')
        ->set('data.to_date', '2026-07-31')
        ->assertFormSet([
            'total_employees' => 2,
            'new_hires' => 2,
            'exits' => 1,
        ]);
});

it('downloads detailed hr report pdf successfully', function (): void {
    actingAs(makeHrReportsAdminUser());

    $record = HrReport::create([
        'from_date' => '2026-07-01',
        'to_date' => '2026-07-31',
        'total_employees' => 10,
        'new_hires' => 2,
        'exits' => 1,
    ]);

    Livewire::test(ListHrReports::class)
        ->callTableAction('download_pdf', $record)
        ->assertFileDownloaded('hr-report-' . $record->id . '.pdf');
});


