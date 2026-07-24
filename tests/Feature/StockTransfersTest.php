<?php

use App\Filament\Resources\StockTransfers\Pages\CreateStockTransfer;
use App\Filament\Resources\StockTransfers\Pages\EditStockTransfer;
use App\Filament\Resources\StockTransfers\Pages\ListStockTransfers;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\StockTransfer;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockTransfersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockTransfersBranches(): array
{
    $company = Company::query()->create(['name' => 'TR Co', 'code' => 'TRCO1', 'status' => true]);
    $fromBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TR From', 'code' => 'TRF01', 'status' => true]);
    $toBranch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TR To', 'code' => 'TRT01', 'status' => true]);

    return compact('fromBranch', 'toBranch');
}

it('redirects guests away from stock transfers list', function (): void {
    get('/admin/stock-transfers')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock transfer page', function (): void {
    get('/admin/stock-transfers/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock transfer', function (): void {
    actingAs(makeStockTransfersAdminUser());

    Livewire::test(CreateStockTransfer::class)
        ->fillForm([
            'from_branch_id' => null,
            'to_branch_id' => null,
            'transfer_date' => null,
            'vehicle_number' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'from_branch_id' => 'required',
            'to_branch_id' => 'required',
            'transfer_date' => 'required',
            'vehicle_number' => 'required',
            'status' => 'required',
        ]);
});

it('performs stock transfer crud operations', function (): void {
    actingAs(makeStockTransfersAdminUser());
    ['fromBranch' => $fromBranch, 'toBranch' => $toBranch] = createStockTransfersBranches();

    Livewire::test(CreateStockTransfer::class)
        ->fillForm([
            'from_branch_id' => $fromBranch->getKey(),
            'to_branch_id' => $toBranch->getKey(),
            'transfer_date' => '2026-07-21',
            'vehicle_number' => 'TN01AB1234',
            'driver_name' => 'Driver One',
            'status' => 'in_transit',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockTransfer::query()->firstOrFail();

    Livewire::test(ListStockTransfers::class)
        ->assertSuccessful()
        ->assertSee('TN01AB1234');

    Livewire::test(EditStockTransfer::class, ['record' => $record->getKey()])
        ->fillForm([
            'from_branch_id' => $fromBranch->getKey(),
            'to_branch_id' => $toBranch->getKey(),
            'transfer_date' => '2026-07-22',
            'vehicle_number' => 'TN01AB9999',
            'driver_name' => 'Driver Two',
            'status' => 'received',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('received');

    Livewire::test(EditStockTransfer::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockTransfer::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
