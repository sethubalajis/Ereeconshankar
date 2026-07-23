<?php

use App\Filament\Resources\SalaryStructures\Pages\CreateSalaryStructure;
use App\Filament\Resources\SalaryStructures\Pages\EditSalaryStructure;
use App\Filament\Resources\SalaryStructures\Pages\ListSalaryStructures;
use App\Models\Role;
use App\Models\SalaryStructure;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeSalaryStructuresAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from salary structures list', function (): void {
    get('/admin/salary-structures')->assertRedirect('/admin/login');
});

it('redirects guests away from create salary structure page', function (): void {
    get('/admin/salary-structures/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a salary structure', function (): void {
    actingAs(makeSalaryStructuresAdminUser());

    Livewire::test(CreateSalaryStructure::class)
        ->fillForm([
            'name' => '',
            'basic_percentage' => null,
            'hra_percentage' => null,
            'special_allowance' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'basic_percentage' => 'required',
            'hra_percentage' => 'required',
            'special_allowance' => 'required',
            'status' => 'required',
        ]);
});

it('performs salary structure crud operations', function (): void {
    actingAs(makeSalaryStructuresAdminUser());

    Livewire::test(CreateSalaryStructure::class)
        ->fillForm([
            'name' => 'Structure A',
            'basic_percentage' => 40,
            'hra_percentage' => 20,
            'other_allowances' => [],
            'special_allowance' => 40,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = SalaryStructure::query()->firstOrFail();

    Livewire::test(ListSalaryStructures::class)
        ->assertSuccessful()
        ->assertSee('Structure A');

    Livewire::test(EditSalaryStructure::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Structure B',
            'basic_percentage' => 45,
            'hra_percentage' => 20,
            'other_allowances' => [],
            'special_allowance' => 35,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Structure B');

    Livewire::test(EditSalaryStructure::class, ['record' => $record->getKey()])->callAction('delete');
    expect(SalaryStructure::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
