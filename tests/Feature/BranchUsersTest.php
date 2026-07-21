<?php

use App\Filament\Resources\BranchUsers\Pages\CreateBranchUser;
use App\Filament\Resources\BranchUsers\Pages\EditBranchUser;
use App\Filament\Resources\BranchUsers\Pages\ListBranchUsers;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchUsersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBranchUsersSetup(): array
{
    $company = Company::query()->create(['name' => 'Branch Users Co', 'code' => 'BUCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Branch Users Branch', 'code' => 'BUBR1', 'status' => true]);
    $user = User::factory()->create();
    return compact('branch', 'user');
}

it('redirects guests away from branch users list', function (): void { get('/admin/branch-users')->assertRedirect('/admin/login'); });
it('redirects guests away from create branch user page', function (): void { get('/admin/branch-users/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a branch user', function (): void {
    actingAs(makeBranchUsersAdminUser());
    Livewire::test(CreateBranchUser::class)
        ->fillForm(['branch_id' => null, 'user_id' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'user_id' => 'required']);
});

it('performs branch user crud operations', function (): void {
    actingAs(makeBranchUsersAdminUser());
    ['branch' => $branch, 'user' => $user] = createBranchUsersSetup();

    Livewire::test(CreateBranchUser::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'user_id' => $user->getKey()])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchUser::query()->firstOrFail();
    Livewire::test(ListBranchUsers::class)->assertSuccessful()->assertSee($user->name);

    $otherUser = User::factory()->create();
    Livewire::test(EditBranchUser::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'user_id' => $otherUser->getKey()])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->user_id)->toBe($otherUser->getKey());

    Livewire::test(EditBranchUser::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchUser::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
