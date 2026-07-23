<?php

use App\Filament\Resources\BranchTargets\Pages\CreateBranchTarget;
use App\Filament\Resources\BranchTargets\Pages\EditBranchTarget;
use App\Filament\Resources\BranchTargets\Pages\ListBranchTargets;
use App\Models\Branch;
use App\Models\BranchTarget;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBranchTargetsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBranchTargetBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Target Co', 'code' => 'TRGCO1', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Target Branch', 'code' => 'TRGBR1', 'status' => true]);
}

it('redirects guests away from branch targets list', function (): void { get('/admin/branch-targets')->assertRedirect('/admin/login'); });
it('redirects guests away from create branch target page', function (): void { get('/admin/branch-targets/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a branch target', function (): void {
    actingAs(makeBranchTargetsAdminUser());
    Livewire::test(CreateBranchTarget::class)
        ->fillForm(['branch_id' => null, 'target_month' => null, 'target_amount' => null, 'achieved_amount' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'target_month' => 'required', 'target_amount' => 'required', 'achieved_amount' => 'required']);
});

it('performs branch target crud operations', function (): void {
    actingAs(makeBranchTargetsAdminUser());
    $branch = createBranchTargetBranch();

    Livewire::test(CreateBranchTarget::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'target_month' => '2026-07-01', 'target_amount' => 50000, 'achieved_amount' => 20000])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BranchTarget::query()->firstOrFail();
    Livewire::test(ListBranchTargets::class)
        ->assertSuccessful()
        ->assertSee(number_format(50000));

    Livewire::test(EditBranchTarget::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'target_month' => '2026-08-01', 'target_amount' => 60000, 'achieved_amount' => 30000])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->target_amount)->toBe(60000.0);

    Livewire::test(EditBranchTarget::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BranchTarget::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
