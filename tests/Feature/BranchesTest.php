<?php

use App\Filament\Resources\Branches\Pages\CreateBranch;
use App\Filament\Resources\Branches\Pages\EditBranch;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createBranchTestCompany(): Company
{
    return Company::query()->create(['name' => 'Branch Test Co', 'code' => 'BTC001', 'status' => true]);
}

it('redirects guests away from branches list', function (): void {
    get('/admin/branches')->assertRedirect('/admin/login');
});

it('redirects guests away from create branch page', function (): void {
    get('/admin/branches/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a branch', function (): void {
    actingAs(makeBranchesAdminUser());

    Livewire::test(CreateBranch::class)
        ->fillForm(['company_id' => null, 'name' => '', 'code' => '', 'status' => true])
        ->call('create')
        ->assertHasFormErrors(['company_id' => 'required', 'name' => 'required', 'code' => 'required']);
});

it('performs branch crud operations', function (): void {
    actingAs(makeBranchesAdminUser());

    $company = createBranchTestCompany();

    Livewire::test(CreateBranch::class)
        ->fillForm(['company_id' => $company->getKey(), 'name' => 'Main Branch', 'code' => 'BR001', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Branch::query()->where('code', 'BR001')->firstOrFail();

    Livewire::test(ListBranches::class)->assertSuccessful()->assertSee('Main Branch');

    Livewire::test(EditBranch::class, ['record' => $record->getKey()])
        ->fillForm(['company_id' => $company->getKey(), 'name' => 'HQ Branch', 'code' => 'BR002', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('HQ Branch');
    expect($record->code)->toBe('BR002');

    Livewire::test(EditBranch::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Branch::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
