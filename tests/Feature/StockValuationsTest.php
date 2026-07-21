<?php

use App\Filament\Resources\StockValuations\Pages\CreateStockValuation;
use App\Filament\Resources\StockValuations\Pages\EditStockValuation;
use App\Filament\Resources\StockValuations\Pages\ListStockValuations;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\StockValuation;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockValuationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockValuationsBranch(): Branch
{
    $company = Company::query()->create(['name' => 'SV Co', 'code' => 'SVCO1', 'status' => true]);

    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'SV Branch', 'code' => 'SVB01', 'status' => true]);
}

it('redirects guests away from stock valuations list', function (): void {
    get('/admin/stock-valuations')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock valuation page', function (): void {
    get('/admin/stock-valuations/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock valuation', function (): void {
    actingAs(makeStockValuationsAdminUser());

    Livewire::test(CreateStockValuation::class)
        ->fillForm([
            'branch_id' => null,
            'valuation_date' => null,
            'total_value' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'branch_id' => 'required',
            'valuation_date' => 'required',
            'total_value' => 'required',
        ]);
});

it('performs stock valuation crud operations', function (): void {
    actingAs(makeStockValuationsAdminUser());
    $branch = createStockValuationsBranch();

    Livewire::test(CreateStockValuation::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'valuation_date' => '2026-07-21',
            'total_value' => 50000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockValuation::query()->firstOrFail();

    Livewire::test(ListStockValuations::class)
        ->assertSuccessful()
        ->assertSee('SV Branch');

    Livewire::test(EditStockValuation::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'valuation_date' => '2026-07-22',
            'total_value' => 55000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_value)->toBe(55000.0);

    Livewire::test(EditStockValuation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockValuation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
