<?php

use App\Filament\Resources\CostCenters\Pages\CreateCostCenter;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CostCenter;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCostCenterValidationAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createCostCenterValidationBranch(): Branch
{
    $company = Company::query()->create([
        'name' => 'Cost Center Validation Co',
        'code' => 'CCVC01',
        'status' => true,
    ]);

    return Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Cost Center Validation Branch',
        'code' => 'CCVB01',
        'status' => true,
    ]);
}

it('redirects guests away from cost centers list', function (): void {
    get('/admin/cost-centers')->assertRedirect('/admin/login');
});

it('validates required fields when creating a cost center', function (): void {
    actingAs(makeCostCenterValidationAdminUser());

    Livewire::test(CreateCostCenter::class)
        ->fillForm([
            'branch_id' => null,
            'name' => '',
            'code' => '',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'branch_id' => 'required',
            'name' => 'required',
            'code' => 'required',
        ]);
});

it('prevents duplicate cost center code at validation level', function (): void {
    actingAs(makeCostCenterValidationAdminUser());

    $branch = createCostCenterValidationBranch();

    CostCenter::query()->create([
        'branch_id' => $branch->getKey(),
        'name' => 'Cost Center Existing',
        'code' => 'CCV001',
        'status' => true,
    ]);

    Livewire::test(CreateCostCenter::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'name' => 'Cost Center New',
            'code' => 'CCV001',
            'status' => true,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'code' => 'unique',
        ]);
});
