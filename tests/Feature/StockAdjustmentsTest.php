<?php

use App\Filament\Resources\StockAdjustments\Pages\CreateStockAdjustment;
use App\Filament\Resources\StockAdjustments\Pages\EditStockAdjustment;
use App\Filament\Resources\StockAdjustments\Pages\ListStockAdjustments;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Stock;
use App\Models\StockAdjustment;
use App\Models\StockLedger;
use App\Models\StockTransaction;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockAdjustmentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockAdjustmentsSetup(): array
{
    $company = Company::query()->create(['name' => 'SA Co', 'code' => 'SACO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'SA Branch', 'code' => 'SAB01', 'status' => true]);
    $product = Product::query()->create(['name' => 'SA Product', 'code' => 'SAP01', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'SA-SKU-1', 'cost_price' => 10, 'selling_price' => 12, 'mrp' => 15, 'is_active' => true]);

    return compact('branch', 'product', 'variant');
}

it('redirects guests away from stock adjustments list', function (): void {
    get('/admin/stock-adjustments')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock adjustment page', function (): void {
    get('/admin/stock-adjustments/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock adjustment', function (): void {
    actingAs(makeStockAdjustmentsAdminUser());

    Livewire::test(CreateStockAdjustment::class)
        ->fillForm([
            'branch_id' => null,
            'product_id' => null,
            'quantity' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'branch_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'status' => 'required',
        ]);
});

it('performs stock adjustment crud operations', function (): void {
    actingAs(makeStockAdjustmentsAdminUser());
    ['branch' => $branch, 'product' => $product, 'variant' => $variant] = createStockAdjustmentsSetup();

    Livewire::test(CreateStockAdjustment::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 5,
            'reason' => 'Initial correction',
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockAdjustment::query()->firstOrFail();

    Livewire::test(ListStockAdjustments::class)
        ->assertSuccessful()
        ->assertSee('SA Product');

    Livewire::test(EditStockAdjustment::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 8,
            'reason' => 'Recounted quantity',
            'status' => 'pending',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(8.0);

    Livewire::test(EditStockAdjustment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockAdjustment::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('reflects approved stock adjustment in stocks transactions and ledgers', function (): void {
    actingAs(makeStockAdjustmentsAdminUser());
    ['branch' => $branch, 'product' => $product, 'variant' => $variant] = createStockAdjustmentsSetup();

    Livewire::test(CreateStockAdjustment::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'warehouse_id' => null,
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 5,
            'reason' => 'Approved correction',
            'status' => 'approved',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $stock = Stock::query()->where([
        'product_id' => $product->getKey(),
        'product_variant_id' => $variant->getKey(),
        'branch_id' => $branch->getKey(),
        'warehouse_id' => null,
    ])->first();

    expect($stock)->not->toBeNull();
    expect((float) $stock->quantity)->toBe(5.0);

    $transaction = StockTransaction::query()
        ->where('stock_id', $stock->getKey())
        ->where('reference_type', 'App\\Models\\StockAdjustment')
        ->latest('id')
        ->first();

    expect($transaction)->not->toBeNull();
    expect($transaction->transaction_type)->toBe('in');
    expect((float) $transaction->quantity)->toBe(5.0);

    $ledger = StockLedger::query()
        ->where('product_id', $product->getKey())
        ->where('product_variant_id', $variant->getKey())
        ->where('branch_id', $branch->getKey())
        ->whereNull('warehouse_id')
        ->latest('id')
        ->first();

    expect($ledger)->not->toBeNull();
    expect((float) $ledger->inward_qty)->toBe(5.0);
    expect((float) $ledger->closing_qty)->toBe(5.0);
});
