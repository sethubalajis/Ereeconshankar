<?php

use App\Filament\Resources\UserBranchAccesses\Pages\CreateUserBranchAccess;
use App\Filament\Resources\UserBranchAccesses\Pages\EditUserBranchAccess;
use App\Filament\Resources\UserBranchAccesses\Pages\ListUserBranchAccesses;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Models\UserBranchAccess;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeUserBranchAccessesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createUserBranchAccessSetup(): array
{
    $user = User::factory()->create();
    $company = Company::query()->create(['name' => 'UBA Co', 'code' => 'UBAC1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'UBA Branch', 'code' => 'UBAB1', 'status' => true]);
    return compact('user', 'branch');
}

it('redirects guests away from user branch accesses list', function (): void {
    get('/admin/user-branch-accesses')->assertRedirect('/admin/login');
});

it('redirects guests away from create user branch access page', function (): void {
    get('/admin/user-branch-accesses/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a user branch access', function (): void {
    actingAs(makeUserBranchAccessesAdminUser());

    Livewire::test(CreateUserBranchAccess::class)
        ->fillForm(['user_id' => null, 'branch_id' => null, 'is_default' => null])
        ->call('create')
        ->assertHasFormErrors(['user_id' => 'required', 'branch_id' => 'required', 'is_default' => 'required']);
});

it('performs user branch access crud operations', function (): void {
    actingAs(makeUserBranchAccessesAdminUser());
    ['user' => $user, 'branch' => $branch] = createUserBranchAccessSetup();

    Livewire::test(CreateUserBranchAccess::class)
        ->fillForm(['user_id' => $user->getKey(), 'branch_id' => $branch->getKey(), 'is_default' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = UserBranchAccess::query()->firstOrFail();
    Livewire::test(ListUserBranchAccesses::class)->assertSuccessful()->assertSee('UBA Branch');

    Livewire::test(EditUserBranchAccess::class, ['record' => $record->getKey()])
        ->fillForm(['user_id' => $user->getKey(), 'branch_id' => $branch->getKey(), 'is_default' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((bool) $record->is_default)->toBeFalse();

    Livewire::test(EditUserBranchAccess::class, ['record' => $record->getKey()])->callAction('delete');
    expect(UserBranchAccess::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
