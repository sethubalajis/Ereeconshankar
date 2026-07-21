<?php

use App\Filament\Resources\PfContributions\Pages\CreatePfContribution;
use App\Filament\Resources\PfContributions\Pages\EditPfContribution;
use App\Filament\Resources\PfContributions\Pages\ListPfContributions;
use App\Models\Employee;
use App\Models\PfContribution;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePfContributionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPfEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'PF', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from pf contributions list', function (): void { get('/admin/pf-contributions')->assertRedirect('/admin/login'); });
it('redirects guests away from create pf contribution page', function (): void { get('/admin/pf-contributions/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pf contribution', function (): void {
    actingAs(makePfContributionsAdminUser());

    Livewire::test(CreatePfContribution::class)
        ->fillForm(['employee_id' => null, 'employee_share' => null, 'employer_share' => null, 'month' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'employee_share' => 'required', 'employer_share' => 'required', 'month' => 'required']);
});

it('performs pf contribution crud operations', function (): void {
    actingAs(makePfContributionsAdminUser());
    $employee = createPfEmployee();

    Livewire::test(CreatePfContribution::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'employee_share' => 1800, 'employer_share' => 1800, 'month' => '2026-07-01'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PfContribution::query()->firstOrFail();
    Livewire::test(ListPfContributions::class)->assertSuccessful()->assertSee('PF');

    Livewire::test(EditPfContribution::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'employee_share' => 2000, 'employer_share' => 2000, 'month' => '2026-08-01'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->employee_share)->toBe(2000.0);

    Livewire::test(EditPfContribution::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PfContribution::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
