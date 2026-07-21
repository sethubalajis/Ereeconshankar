<?php

use App\Filament\Resources\InvestmentTypes\Pages\CreateInvestmentType;
use App\Filament\Resources\InvestmentTypes\Pages\EditInvestmentType;
use App\Filament\Resources\InvestmentTypes\Pages\ListInvestmentTypes;
use App\Models\InvestmentType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeInvestmentTypesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from investment types list', function (): void { get('/admin/investment-types')->assertRedirect('/admin/login'); });
it('redirects guests away from create investment type page', function (): void { get('/admin/investment-types/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an investment type', function (): void {
    actingAs(makeInvestmentTypesAdminUser());

    Livewire::test(CreateInvestmentType::class)
        ->fillForm(['name' => '', 'section' => '', 'max_limit' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'status' => 'required']);
});

it('performs investment type crud operations', function (): void {
    actingAs(makeInvestmentTypesAdminUser());

    Livewire::test(CreateInvestmentType::class)
        ->fillForm(['name' => 'PPF', 'section' => '80C', 'max_limit' => 150000, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = InvestmentType::query()->firstOrFail();
    Livewire::test(ListInvestmentTypes::class)->assertSuccessful()->assertSee('PPF');

    Livewire::test(EditInvestmentType::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'NPS', 'section' => '80CCD', 'max_limit' => 200000, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('NPS');

    Livewire::test(EditInvestmentType::class, ['record' => $record->getKey()])->callAction('delete');
    expect(InvestmentType::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
