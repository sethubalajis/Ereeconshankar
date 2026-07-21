<?php

use App\Filament\Resources\ProfessionalTaxes\Pages\CreateProfessionalTax;
use App\Filament\Resources\ProfessionalTaxes\Pages\EditProfessionalTax;
use App\Filament\Resources\ProfessionalTaxes\Pages\ListProfessionalTaxes;
use App\Models\Employee;
use App\Models\ProfessionalTax;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProfessionalTaxesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createProfessionalTaxEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'PT', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from professional taxes list', function (): void { get('/admin/professional-taxes')->assertRedirect('/admin/login'); });
it('redirects guests away from create professional tax page', function (): void { get('/admin/professional-taxes/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a professional tax', function (): void {
    actingAs(makeProfessionalTaxesAdminUser());

    Livewire::test(CreateProfessionalTax::class)
        ->fillForm(['employee_id' => null, 'state_id' => null, 'amount' => null, 'month' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'amount' => 'required', 'month' => 'required']);
});

it('performs professional tax crud operations', function (): void {
    actingAs(makeProfessionalTaxesAdminUser());
    $employee = createProfessionalTaxEmployee();

    Livewire::test(CreateProfessionalTax::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'state_id' => null, 'amount' => 200, 'month' => '2026-07-01'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProfessionalTax::query()->firstOrFail();
    Livewire::test(ListProfessionalTaxes::class)->assertSuccessful()->assertSee('PT');

    Livewire::test(EditProfessionalTax::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'state_id' => null, 'amount' => 250, 'month' => '2026-08-01'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(250.0);

    Livewire::test(EditProfessionalTax::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ProfessionalTax::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
