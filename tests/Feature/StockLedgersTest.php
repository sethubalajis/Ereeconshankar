<?php

use App\Filament\Resources\StockLedgers\Pages\CreateStockLedger;
use App\Filament\Resources\StockLedgers\Pages\EditStockLedger;
use App\Filament\Resources\StockLedgers\Pages\ListStockLedgers;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockLedger;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockLedgersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockLedgersSetup(): array
{
    $company = Company::query()->create(['name' => 'SL Co', 'code' => 'SLCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'SL Branch', 'code' => 'SLB01', 'status' => true]);
    $product = Product::query()->create(['name' => 'SL Product', 'code' => 'SLP01', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'SL-SKU-1', 'cost_price' => 11, 'selling_price' => 14, 'mrp' => 16, 'is_active' => true]);

    return compact('branch', 'product', 'variant');
}

it('redirects guests away from stock ledgers list', function (): void {
    get('/admin/stock-ledgers')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock ledger page', function (): void {
    get('/admin/stock-ledgers/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock ledger', function (): void {
    actingAs(makeStockLedgersAdminUser());

    Livewire::test(CreateStockLedger::class)
        ->fillForm([
            'product_id' => null,
            'branch_id' => null,
            'transaction_date' => null,
            'opening_qty' => null,
            'inward_qty' => null,
            'outward_qty' => null,
            'closing_qty' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'product_id' => 'required',
            'branch_id' => 'required',
            'transaction_date' => 'required',
            'opening_qty' => 'required',
            'inward_qty' => 'required',
            'outward_qty' => 'required',
            'closing_qty' => 'required',
        ]);
});

it('performs stock ledger crud operations', function (): void {
    actingAs(makeStockLedgersAdminUser());
    ['branch' => $branch, 'product' => $product, 'variant' => $variant] = createStockLedgersSetup();

    Livewire::test(CreateStockLedger::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'transaction_date' => '2026-07-21',
            'opening_qty' => 10,
            'inward_qty' => 5,
            'outward_qty' => 2,
            'closing_qty' => 13,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockLedger::query()->firstOrFail();

    Livewire::test(ListStockLedgers::class)
        ->assertSuccessful()
        ->assertSee('SL Product');

    Livewire::test(EditStockLedger::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'transaction_date' => '2026-07-22',
            'opening_qty' => 13,
            'inward_qty' => 1,
            'outward_qty' => 3,
            'closing_qty' => 11,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->closing_qty)->toBe(11.0);

    Livewire::test(EditStockLedger::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockLedger::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
