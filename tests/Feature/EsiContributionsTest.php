<?php

use App\Filament\Resources\EsiContributions\Pages\CreateEsiContribution;
use App\Filament\Resources\EsiContributions\Pages\EditEsiContribution;
use App\Filament\Resources\EsiContributions\Pages\ListEsiContributions;
use App\Models\Employee;
use App\Models\EsiContribution;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEsiContributionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEsiEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'ESI', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from esi contributions list', function (): void { get('/admin/esi-contributions')->assertRedirect('/admin/login'); });
it('redirects guests away from create esi contribution page', function (): void { get('/admin/esi-contributions/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an esi contribution', function (): void {
    actingAs(makeEsiContributionsAdminUser());
    Livewire::test(CreateEsiContribution::class)
        ->fillForm(['employee_id' => null, 'employee_share' => null, 'employer_share' => null, 'month' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'employee_share' => 'required', 'employer_share' => 'required', 'month' => 'required']);
});

it('performs esi contribution crud operations', function (): void {
    actingAs(makeEsiContributionsAdminUser());
    $employee = createEsiEmployee();

    Livewire::test(CreateEsiContribution::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'employee_share' => 250, 'employer_share' => 750, 'month' => '2026-07-01'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EsiContribution::query()->firstOrFail();
    Livewire::test(ListEsiContributions::class)->assertSuccessful()->assertSee('250');

    Livewire::test(EditEsiContribution::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'employee_share' => 300, 'employer_share' => 900, 'month' => '2026-08-01'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->employee_share)->toBe(300.0);

    Livewire::test(EditEsiContribution::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EsiContribution::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
