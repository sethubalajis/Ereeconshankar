<?php

use App\Filament\Resources\LoyaltyPrograms\Pages\CreateLoyaltyProgram;
use App\Filament\Resources\LoyaltyPrograms\Pages\EditLoyaltyProgram;
use App\Filament\Resources\LoyaltyPrograms\Pages\ListLoyaltyPrograms;
use App\Models\LoyaltyProgram;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLoyaltyProgramsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from loyalty programs list', function (): void { get('/admin/loyalty-programs')->assertRedirect('/admin/login'); });
it('redirects guests away from create loyalty program page', function (): void { get('/admin/loyalty-programs/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a loyalty program', function (): void {
    actingAs(makeLoyaltyProgramsAdminUser());

    Livewire::test(CreateLoyaltyProgram::class)
        ->fillForm([
            'name' => '',
            'points_per_amount' => null,
            'amount_per_point' => null,
            'minimum_points_to_redeem' => null,
            'value_per_point' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'points_per_amount' => 'required',
            'amount_per_point' => 'required',
            'minimum_points_to_redeem' => 'required',
            'value_per_point' => 'required',
            'status' => 'required',
        ]);
});

it('performs loyalty program crud operations', function (): void {
    actingAs(makeLoyaltyProgramsAdminUser());

    Livewire::test(CreateLoyaltyProgram::class)
        ->fillForm([
            'name' => 'Gold Loyalty',
            'points_per_amount' => 1,
            'amount_per_point' => 100,
            'minimum_points_to_redeem' => 200,
            'value_per_point' => 1,
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = LoyaltyProgram::query()->firstOrFail();
    Livewire::test(ListLoyaltyPrograms::class)->assertSuccessful()->assertSee('Gold Loyalty');

    Livewire::test(EditLoyaltyProgram::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Platinum Loyalty',
            'points_per_amount' => 2,
            'amount_per_point' => 120,
            'minimum_points_to_redeem' => 250,
            'value_per_point' => 1.5,
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Platinum Loyalty');

    Livewire::test(EditLoyaltyProgram::class, ['record' => $record->getKey()])->callAction('delete');
    expect(LoyaltyProgram::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
