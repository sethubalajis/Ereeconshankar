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
        ->fillForm(['report_type' => '', 'as_of_date' => null, 'data' => ''])
        ->call('create')
        ->assertHasFormErrors(['report_type' => 'required', 'as_of_date' => 'required']);
});

it('performs financial report crud operations', function (): void {
    actingAs(makeFinancialReportsAdminUser());

    Livewire::test(CreateFinancialReport::class)
        ->fillForm(['report_type' => 'PnL', 'as_of_date' => '2026-07-21', 'data' => '{"profit":15000}'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = FinancialReport::query()->firstOrFail();
    Livewire::test(ListFinancialReports::class)->assertSuccessful()->assertSee('PnL');

    Livewire::test(EditFinancialReport::class, ['record' => $record->getKey()])
        ->fillForm(['report_type' => 'BalanceSheet', 'as_of_date' => '2026-08-01', 'data' => '{"assets":50000}'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->report_type)->toBe('BalanceSheet');

    Livewire::test(EditFinancialReport::class, ['record' => $record->getKey()])->callAction('delete');
    expect(FinancialReport::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
