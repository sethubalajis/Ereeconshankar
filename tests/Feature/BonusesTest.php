<?php

use App\Filament\Resources\Bonuses\Pages\CreateBonus;
use App\Filament\Resources\Bonuses\Pages\EditBonus;
use App\Filament\Resources\Bonuses\Pages\ListBonuses;
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBonusesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBonusEmployee(): Employee
{
    return Employee::query()->create(['first_name' => 'Bonus', 'last_name' => 'Employee', 'status' => 'active']);
}

it('redirects guests away from bonuses list', function (): void { get('/admin/bonuses')->assertRedirect('/admin/login'); });
it('redirects guests away from create bonus page', function (): void { get('/admin/bonuses/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a bonus', function (): void {
    actingAs(makeBonusesAdminUser());
    Livewire::test(CreateBonus::class)
        ->fillForm(['employee_id' => null, 'bonus_type' => '', 'amount' => null, 'payout_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'bonus_type' => 'required', 'amount' => 'required', 'status' => 'required']);
});

it('performs bonus crud operations', function (): void {
    actingAs(makeBonusesAdminUser());
    $employee = createBonusEmployee();

    Livewire::test(CreateBonus::class)
        ->fillForm(['employee_id' => $employee->getKey(), 'bonus_type' => 'Performance', 'amount' => 500, 'payout_date' => '2026-07-21', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Bonus::query()->firstOrFail();
    Livewire::test(ListBonuses::class)->assertSuccessful()->assertSee('Performance');

    Livewire::test(EditBonus::class, ['record' => $record->getKey()])
        ->fillForm(['employee_id' => $employee->getKey(), 'bonus_type' => 'Festival', 'amount' => 750, 'payout_date' => '2026-07-22', 'status' => 'approved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->bonus_type)->toBe('Festival');

    Livewire::test(EditBonus::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Bonus::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
