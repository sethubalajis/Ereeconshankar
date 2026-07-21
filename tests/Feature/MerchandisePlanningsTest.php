<?php

use App\Filament\Resources\MerchandisePlannings\Pages\CreateMerchandisePlanning;
use App\Filament\Resources\MerchandisePlannings\Pages\EditMerchandisePlanning;
use App\Filament\Resources\MerchandisePlannings\Pages\ListMerchandisePlannings;
use App\Models\FinancialYear;
use App\Models\MerchandisePlanning;
use App\Models\Role;
use App\Models\Season;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeMerchandisePlanningsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createMerchandisePlanningSetup(): array
{
    $financialYear = FinancialYear::query()->create([
        'name' => 'FY 2026-27',
        'start_date' => '2026-04-01',
        'end_date' => '2027-03-31',
        'is_active' => true,
        'is_closed' => false,
    ]);

    $season = Season::query()->create([
        'name' => 'Summer',
        'code' => 'SUM01',
        'start_date' => '2026-03-01',
        'end_date' => '2026-06-30',
        'status' => true,
    ]);

    return compact('financialYear', 'season');
}

it('redirects guests away from merchandise plannings list', function (): void { get('/admin/merchandise-plannings')->assertRedirect('/admin/login'); });
it('redirects guests away from create merchandise planning page', function (): void { get('/admin/merchandise-plannings/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a merchandise planning', function (): void {
    actingAs(makeMerchandisePlanningsAdminUser());

    Livewire::test(CreateMerchandisePlanning::class)
        ->fillForm(['financial_year_id' => null, 'season_id' => null, 'target_sales' => null, 'planned_budget' => null])
        ->call('create')
        ->assertHasFormErrors(['financial_year_id' => 'required', 'season_id' => 'required', 'target_sales' => 'required', 'planned_budget' => 'required']);
});

it('performs merchandise planning crud operations', function (): void {
    actingAs(makeMerchandisePlanningsAdminUser());
    ['financialYear' => $financialYear, 'season' => $season] = createMerchandisePlanningSetup();

    Livewire::test(CreateMerchandisePlanning::class)
        ->fillForm(['financial_year_id' => $financialYear->getKey(), 'season_id' => $season->getKey(), 'target_sales' => 250000, 'planned_budget' => 180000])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = MerchandisePlanning::query()->firstOrFail();
    Livewire::test(ListMerchandisePlannings::class)->assertSuccessful()->assertSee('Summer');

    Livewire::test(EditMerchandisePlanning::class, ['record' => $record->getKey()])
        ->fillForm(['financial_year_id' => $financialYear->getKey(), 'season_id' => $season->getKey(), 'target_sales' => 300000, 'planned_budget' => 210000])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->target_sales)->toBe(300000.0);

    Livewire::test(EditMerchandisePlanning::class, ['record' => $record->getKey()])->callAction('delete');
    expect(MerchandisePlanning::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
