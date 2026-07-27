<?php

use App\Filament\Resources\FinancialReports\Pages\CreateFinancialReport;
use App\Filament\Resources\FinancialReports\Pages\EditFinancialReport;
use App\Filament\Resources\FinancialReports\Pages\ListFinancialReports;
use App\Models\FinancialReport;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeFinancialReportsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from financial reports list', function (): void { get('/admin/financial-reports')->assertRedirect('/admin/login'); });
it('redirects guests away from create financial report page', function (): void { get('/admin/financial-reports/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a financial report', function (): void {
    actingAs(makeFinancialReportsAdminUser());
    Livewire::test(CreateFinancialReport::class)
        ->fillForm(['report_type' => '', 'from_date' => null, 'to_date' => null, 'data' => ''])
        ->call('create')
        ->assertHasFormErrors(['report_type' => 'required', 'from_date' => 'required', 'to_date' => 'required']);
});

it('performs financial report crud operations with date range', function (): void {
    actingAs(makeFinancialReportsAdminUser());

    Livewire::test(CreateFinancialReport::class)
        ->fillForm([
            'report_type' => 'Profit & Loss',
            'from_date' => '2026-07-01',
            'to_date' => '2026-07-31',
            'as_of_date' => '2026-07-31',
            'data' => 'Initial Financial Data',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = FinancialReport::query()->firstOrFail();
    expect($record->from_date->format('Y-m-d'))->toBe('2026-07-01');
    expect($record->to_date->format('Y-m-d'))->toBe('2026-07-31');

    Livewire::test(ListFinancialReports::class)->assertSuccessful()->assertSee('Profit & Loss');

    Livewire::test(EditFinancialReport::class, ['record' => $record->getKey()])
        ->fillForm([
            'report_type' => 'Balance Sheet',
            'from_date' => '2026-07-01',
            'to_date' => '2026-08-31',
            'as_of_date' => '2026-08-31',
            'data' => 'Updated Financial Data',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->to_date->format('Y-m-d'))->toBe('2026-08-31');

    Livewire::test(EditFinancialReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(FinancialReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

