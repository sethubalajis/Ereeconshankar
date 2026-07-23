<?php

use App\Filament\Resources\Incentives\Pages\CreateIncentive;
use App\Filament\Resources\Incentives\Pages\EditIncentive;
use App\Filament\Resources\Incentives\Pages\ListIncentives;
use App\Models\Employee;
use App\Models\Incentive;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeIncentivesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createIncentiveEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Incentive', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from incentives list', function (): void { get('/admin/incentives')->assertRedirect('/admin/login'); });
it('redirects guests away from create incentive page', function (): void { get('/admin/incentives/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an incentive', function (): void {
    actingAs(makeIncentivesAdminUser());
    Livewire::test(CreateIncentive::class)
        ->fillForm(['employee_id' => null, 'month' => null, 'amount' => null, 'remarks' => ''])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'month' => 'required', 'amount' => 'required']);
});

it('performs incentive crud operations', function (): void {
    actingAs(makeIncentivesAdminUser());
    $employee = createIncentiveEmployee();

    Livewire::test(CreateIncentive::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'month' => '2026-07-01', 'amount' => 5000, 'remarks' => 'Target bonus'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Incentive::query()->firstOrFail();
    Livewire::test(ListIncentives::class)->assertSuccessful()->assertSee(number_format(5000));

    Livewire::test(EditIncentive::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'month' => '2026-08-01', 'amount' => 6000, 'remarks' => 'Updated'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditIncentive::class, ['record' => $record->getKey()])->callAction('delete');
});
