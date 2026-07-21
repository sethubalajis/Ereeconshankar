<?php

use App\Filament\Resources\FinancialYears\Pages\CreateFinancialYear;
use App\Filament\Resources\FinancialYears\Pages\EditFinancialYear;
use App\Filament\Resources\FinancialYears\Pages\ListFinancialYears;
use App\Models\FinancialYear;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeFinancialYearAdminUser(): User
{
    $role = Role::query()->firstOrCreate([
        'name' => 'Super Admin',
        'guard_name' => 'web',
    ]);

    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from financial years list', function (): void {
    get('/admin/financial-years')->assertRedirect('/admin/login');
});

it('redirects guests away from create financial year page', function (): void {
    get('/admin/financial-years/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a financial year', function (): void {
    actingAs(makeFinancialYearAdminUser());

    Livewire::test(CreateFinancialYear::class)
        ->fillForm([
            'name' => '',
            'start_date' => null,
            'end_date' => null,
            'is_active' => true,
            'is_closed' => false,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
});

it('performs financial year crud operations', function (): void {
    actingAs(makeFinancialYearAdminUser());

    Livewire::test(CreateFinancialYear::class)
        ->fillForm([
            'name' => 'FY 2026-2027',
            'start_date' => '2026-04-01',
            'end_date' => '2027-03-31',
            'is_active' => true,
            'is_closed' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = FinancialYear::query()->where('name', 'FY 2026-2027')->firstOrFail();

    Livewire::test(ListFinancialYears::class)
        ->assertSuccessful()
        ->assertSee('FY 2026-2027');

    Livewire::test(EditFinancialYear::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'FY 2027-2028',
            'start_date' => '2027-04-01',
            'end_date' => '2028-03-31',
            'is_active' => false,
            'is_closed' => true,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('FY 2027-2028');
    expect((bool) $record->is_active)->toBeFalse();
    expect((bool) $record->is_closed)->toBeTrue();

    Livewire::test(EditFinancialYear::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(FinancialYear::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
