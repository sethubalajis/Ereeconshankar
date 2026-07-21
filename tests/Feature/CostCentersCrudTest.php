<?php

use App\Filament\Resources\CostCenters\Pages\CreateCostCenter;
use App\Filament\Resources\CostCenters\Pages\EditCostCenter;
use App\Filament\Resources\CostCenters\Pages\ListCostCenters;
use App\Models\Branch;
use App\Models\Company;
use App\Models\CostCenter;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCostCenterCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs cost center crud operations', function (): void {
    actingAs(makeCostCenterCrudAdminUser());

    $company = Company::query()->create([
        'name' => 'Cost Center Co',
        'code' => 'CCCO01',
        'status' => true,
    ]);

    $branch = Branch::query()->create([
        'company_id' => $company->getKey(),
        'name' => 'Cost Center Branch',
        'code' => 'CCBR01',
        'status' => true,
    ]);

    Livewire::test(CreateCostCenter::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'name' => 'Cost Center CRUD',
            'code' => 'CC001',
            'description' => 'Cost center create test',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CostCenter::query()->where('code', 'CC001')->firstOrFail();

    Livewire::test(ListCostCenters::class)
        ->assertSuccessful()
        ->assertSee($record->name)
        ->assertSee($record->code);

    Livewire::test(EditCostCenter::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'name' => 'Cost Center CRUD Updated',
            'code' => 'CC002',
            'description' => 'Cost center update test',
            'status' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Cost Center CRUD Updated');
    expect($record->code)->toBe('CC002');

    Livewire::test(EditCostCenter::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(CostCenter::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
