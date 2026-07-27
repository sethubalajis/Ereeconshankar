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
