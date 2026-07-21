<?php

use App\Filament\Resources\StockTransactions\Pages\CreateStockTransaction;
use App\Filament\Resources\StockTransactions\Pages\EditStockTransaction;
use App\Filament\Resources\StockTransactions\Pages\ListStockTransactions;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Stock;
use App\Models\StockTransaction;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockTransactionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockTransactionsSetup(): Stock
{
    $company = Company::query()->create(['name' => 'TX Co', 'code' => 'TXCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TX Branch', 'code' => 'TXB01', 'status' => true]);
    $product = Product::query()->create(['name' => 'TX Product', 'code' => 'TXP01', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'TX-SKU-1', 'cost_price' => 7, 'selling_price' => 9, 'mrp' => 11, 'is_active' => true]);

    return Stock::query()->create([
        'product_id' => $product->getKey(),
        'product_variant_id' => $variant->getKey(),
        'branch_id' => $branch->getKey(),
        'quantity' => 30,
        'avg_cost' => 7,
    ]);
}

it('redirects guests away from stock transactions list', function (): void {
    get('/admin/stock-transactions')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock transaction page', function (): void {
    get('/admin/stock-transactions/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock transaction', function (): void {
    actingAs(makeStockTransactionsAdminUser());

    Livewire::test(CreateStockTransaction::class)
        ->fillForm([
            'stock_id' => null,
            'transaction_type' => null,
            'quantity' => null,
            'reference' => '',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'stock_id' => 'required',
            'transaction_type' => 'required',
            'quantity' => 'required',
            'reference' => 'required',
        ]);
});

it('performs stock transaction crud operations', function (): void {
    actingAs(makeStockTransactionsAdminUser());
    $stock = createStockTransactionsSetup();

    Livewire::test(CreateStockTransaction::class)
        ->fillForm([
            'stock_id' => $stock->getKey(),
            'transaction_type' => 'in',
            'quantity' => 5,
            'reference' => 'TX-REF-1',
            'reference_type' => 'App\\Models\\StockAdjustment',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockTransaction::query()->firstOrFail();

    Livewire::test(ListStockTransactions::class)
        ->assertSuccessful()
        ->assertSee('TX-REF-1');

    Livewire::test(EditStockTransaction::class, ['record' => $record->getKey()])
        ->fillForm([
            'stock_id' => $stock->getKey(),
            'transaction_type' => 'out',
            'quantity' => 2,
            'reference' => 'TX-REF-2',
            'reference_type' => 'App\\Models\\StockTransfer',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(2.0);

    Livewire::test(EditStockTransaction::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockTransaction::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
