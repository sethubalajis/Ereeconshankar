<?php

use App\Filament\Resources\SalaryComponents\Pages\CreateSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\EditSalaryComponent;
use App\Filament\Resources\SalaryComponents\Pages\ListSalaryComponents;
use App\Models\Role;
use App\Models\SalaryComponent;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSalaryComponentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from salary components list', function (): void {
    get('/admin/salary-components')->assertRedirect('/admin/login');
});

it('redirects guests away from create salary component page', function (): void {
    get('/admin/salary-components/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a salary component', function (): void {
    actingAs(makeSalaryComponentsAdminUser());

    Livewire::test(CreateSalaryComponent::class)
        ->fillForm([
            'name' => '',
            'type' => null,
            'is_taxable' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'type' => 'required',
            'is_taxable' => 'required',
            'status' => 'required',
        ]);
});

it('performs salary component crud operations', function (): void {
    actingAs(makeSalaryComponentsAdminUser());

    Livewire::test(CreateSalaryComponent::class)
        ->fillForm([
            'name' => 'Basic Pay',
            'type' => 'earning',
            'is_taxable' => true,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = SalaryComponent::query()->firstOrFail();

    Livewire::test(ListSalaryComponents::class)
        ->assertSuccessful()
        ->assertSee('Basic Pay');

    Livewire::test(EditSalaryComponent::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Basic Salary',
            'type' => 'earning',
            'is_taxable' => true,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Basic Salary');

    Livewire::test(EditSalaryComponent::class, ['record' => $record->getKey()])->callAction('delete');
    expect(SalaryComponent::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
