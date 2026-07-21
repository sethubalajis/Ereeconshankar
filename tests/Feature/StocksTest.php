<?php

use App\Filament\Resources\Stocks\Pages\CreateStock;
use App\Filament\Resources\Stocks\Pages\EditStock;
use App\Filament\Resources\Stocks\Pages\ListStocks;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Stock;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStocksAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStocksSetup(): array
{
    $company = Company::query()->create(['name' => 'ST Co', 'code' => 'STCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'ST Branch', 'code' => 'STB01', 'status' => true]);
    $product = Product::query()->create(['name' => 'ST Product', 'code' => 'STP01', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'ST-SKU-1', 'cost_price' => 8, 'selling_price' => 10, 'mrp' => 12, 'is_active' => true]);

    return compact('branch', 'product', 'variant');
}

it('redirects guests away from stocks list', function (): void {
    get('/admin/stocks')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock page', function (): void {
    get('/admin/stocks/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock', function (): void {
    actingAs(makeStocksAdminUser());

    Livewire::test(CreateStock::class)
        ->fillForm([
            'product_id' => null,
            'branch_id' => null,
            'quantity' => null,
            'avg_cost' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'product_id' => 'required',
            'branch_id' => 'required',
            'quantity' => 'required',
            'avg_cost' => 'required',
        ]);
});

it('performs stock crud operations', function (): void {
    actingAs(makeStocksAdminUser());
    ['branch' => $branch, 'product' => $product, 'variant' => $variant] = createStocksSetup();

    Livewire::test(CreateStock::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'quantity' => 20,
            'avg_cost' => 8,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Stock::query()->firstOrFail();

    Livewire::test(ListStocks::class)
        ->assertSuccessful()
        ->assertSee('ST Product');

    Livewire::test(EditStock::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'quantity' => 25,
            'avg_cost' => 9,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(25.0);

    Livewire::test(EditStock::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Stock::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
