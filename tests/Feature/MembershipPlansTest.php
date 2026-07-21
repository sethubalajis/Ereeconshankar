<?php

use App\Filament\Resources\MembershipPlans\Pages\CreateMembershipPlan;
use App\Filament\Resources\MembershipPlans\Pages\EditMembershipPlan;
use App\Filament\Resources\MembershipPlans\Pages\ListMembershipPlans;
use App\Models\MembershipPlan;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeMembershipPlansAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from membership plans list', function (): void { get('/admin/membership-plans')->assertRedirect('/admin/login'); });
it('redirects guests away from create membership plan page', function (): void { get('/admin/membership-plans/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a membership plan', function (): void {
    actingAs(makeMembershipPlansAdminUser());

    Livewire::test(CreateMembershipPlan::class)
        ->fillForm(['name' => '', 'fee' => null, 'duration_months' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'fee' => 'required', 'duration_months' => 'required', 'status' => 'required']);
});

it('performs membership plan crud operations', function (): void {
    actingAs(makeMembershipPlansAdminUser());

    Livewire::test(CreateMembershipPlan::class)
        ->fillForm(['name' => 'Silver Plan', 'fee' => 999, 'duration_months' => 12, 'upgrade_threshold' => 50000, 'benefits_description' => 'Basic benefits', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = MembershipPlan::query()->firstOrFail();
    Livewire::test(ListMembershipPlans::class)->assertSuccessful()->assertSee('Silver Plan');

    Livewire::test(EditMembershipPlan::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Gold Plan', 'fee' => 1999, 'duration_months' => 24, 'upgrade_threshold' => 100000, 'benefits_description' => 'Premium benefits', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Gold Plan');

    Livewire::test(EditMembershipPlan::class, ['record' => $record->getKey()])->callAction('delete');
    expect(MembershipPlan::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
