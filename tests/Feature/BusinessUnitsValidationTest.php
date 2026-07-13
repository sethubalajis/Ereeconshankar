<?php

use App\Filament\Resources\BusinessUnits\Pages\CreateBusinessUnit;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBusinessUnitValidationAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('prevents duplicate business unit name at validation level', function (): void {
    actingAs(makeBusinessUnitValidationAdminUser());

    $company = Company::factory()->create();

    BusinessUnit::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'BU DUP Name',
        'code' => 'BUV001',
        'description' => 'Existing row',
        'status' => true,
    ]);

    Livewire::test(CreateBusinessUnit::class)
        ->fillForm([
            'company_id' => $company->getKey(),
            'name' => 'BU DUP Name',
            'code' => 'BUV002',
            'description' => 'Duplicate name attempt',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'unique',
        ]);
});

it('prevents duplicate business unit code at validation level', function (): void {
    actingAs(makeBusinessUnitValidationAdminUser());

    $company = Company::factory()->create();

    BusinessUnit::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'BU DUP Code 1',
        'code' => 'BUV003',
        'description' => 'Existing row',
        'status' => true,
    ]);

    Livewire::test(CreateBusinessUnit::class)
        ->fillForm([
            'company_id' => $company->getKey(),
            'name' => 'BU DUP Code 2',
            'code' => 'BUV003',
            'description' => 'Duplicate code attempt',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'code' => 'unique',
        ]);
});
