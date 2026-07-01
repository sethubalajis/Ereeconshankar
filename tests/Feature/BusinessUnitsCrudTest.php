<?php

use App\Filament\Resources\BusinessUnits\Pages\CreateBusinessUnit;
use App\Filament\Resources\BusinessUnits\Pages\EditBusinessUnit;
use App\Filament\Resources\BusinessUnits\Pages\ListBusinessUnits;
use App\Models\BusinessUnit;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBusinessUnitCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('creates a business unit record', function (): void {
    actingAs(makeBusinessUnitCrudAdminUser());

    $company = Company::factory()->create();

    Livewire::test(CreateBusinessUnit::class)
        ->fillForm([
            'company_id' => $company->getKey(),
            'name' => 'BU CRUD Create',
            'code' => 'BUC001',
            'description' => 'Created from CRUD test',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(BusinessUnit::query()->where('code', 'BUC001')->exists())->toBeTrue();
});

it('reads business units from list page', function (): void {
    actingAs(makeBusinessUnitCrudAdminUser());

    $company = Company::factory()->create();
    $businessUnit = BusinessUnit::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'BU CRUD Read',
        'code' => 'BUC002',
        'description' => 'Read test row',
        'status' => true,
    ]);

    Livewire::test(ListBusinessUnits::class)
        ->assertSuccessful()
        ->assertSee($businessUnit->name)
        ->assertSee($businessUnit->code);
});

it('updates a business unit record', function (): void {
    actingAs(makeBusinessUnitCrudAdminUser());

    $company = Company::factory()->create();
    $businessUnit = BusinessUnit::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'BU CRUD Update Old',
        'code' => 'BUC003',
        'description' => 'Old description',
        'status' => true,
    ]);

    Livewire::test(EditBusinessUnit::class, ['record' => $businessUnit->getKey()])
        ->fillForm([
            'company_id' => $company->getKey(),
            'name' => 'BU CRUD Update New',
            'code' => 'BUC003U',
            'description' => 'Updated description',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $businessUnit->refresh();

    expect($businessUnit->name)->toBe('BU CRUD Update New');
    expect($businessUnit->code)->toBe('BUC003U');
});

it('deletes a business unit record', function (): void {
    actingAs(makeBusinessUnitCrudAdminUser());

    $company = Company::factory()->create();
    $businessUnit = BusinessUnit::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'BU CRUD Delete',
        'code' => 'BUC004',
        'description' => 'Delete test row',
        'status' => true,
    ]);

    Livewire::test(EditBusinessUnit::class, ['record' => $businessUnit->getKey()])
        ->callAction('delete');

    expect(BusinessUnit::query()->whereKey($businessUnit->getKey())->exists())->toBeFalse();
});
