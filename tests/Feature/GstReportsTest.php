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
