<?php

use App\Filament\Resources\FinancialReports\Pages\CreateFinancialReport;
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

