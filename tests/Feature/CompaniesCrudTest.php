<?php

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCompanyCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('creates a company record', function (): void {
    actingAs(makeCompanyCrudAdminUser());

    Livewire::test(CreateCompany::class)
        ->fillForm([
            'name' => 'CRUD Create Co',
            'code' => 'CRUD001',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Company::query()->where('code', 'CRUD001')->exists())->toBeTrue();
});

it('reads companies from list page', function (): void {
    actingAs(makeCompanyCrudAdminUser());

    $company = Company::factory()->create([
        'name' => 'CRUD Read Co',
        'code' => 'CRUD002',
    ]);

    Livewire::test(ListCompanies::class)
        ->assertSuccessful()
        ->assertSee($company->name)
        ->assertSee($company->code);
});

it('updates a company record', function (): void {
    actingAs(makeCompanyCrudAdminUser());

    $company = Company::factory()->create([
        'name' => 'CRUD Update Old',
        'code' => 'CRUD003',
    ]);

    Livewire::test(EditCompany::class, ['record' => $company->getKey()])
        ->fillForm([
            'name' => 'CRUD Update New',
            'code' => 'CRUD003U',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $company->refresh();

    expect($company->name)->toBe('CRUD Update New');
    expect($company->code)->toBe('CRUD003U');
});

it('deletes a company record', function (): void {
    actingAs(makeCompanyCrudAdminUser());

    $company = Company::factory()->create([
        'name' => 'CRUD Delete Co',
        'code' => 'CRUD004',
    ]);

    Livewire::test(EditCompany::class, ['record' => $company->getKey()])
        ->callAction('delete');

    expect(Company::query()->whereKey($company->getKey())->exists())->toBeFalse();
});
