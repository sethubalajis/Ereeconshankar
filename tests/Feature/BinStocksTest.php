<?php

use App\Filament\Resources\BinStocks\Pages\CreateBinStock;
use App\Filament\Resources\BinStocks\Pages\EditBinStock;
use App\Filament\Resources\BinStocks\Pages\ListBinStocks;
use App\Models\Asset; // not used, but keep imports minimal? remove later if unused
use App\Models\Bin;
use App\Models\BinStock;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBinStocksAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createBinStockSetup(): array
{
    $company = Company::query()->create(['name' => 'Bin Stock Co', 'code' => 'BSC001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Bin Stock Branch', 'code' => 'BSB001', 'status' => true]);
    $warehouse = App\Models\Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Warehouse', 'code' => 'WHB001', 'status' => true]);
    $zone = App\Models\WarehouseZone::query()->create(['warehouse_id' => $warehouse->getKey(), 'name' => 'Zone', 'code' => 'ZN001', 'type' => 'Storage']);
    $rack = App\Models\Rack::query()->create(['warehouse_zone_id' => $zone->getKey(), 'name' => 'Rack', 'code' => 'RCKB01']);
    $bin = Bin::query()->create(['rack_id' => $rack->getKey(), 'name' => 'Bin A', 'code' => 'BINB01']);
    $category = ProductCategory::query()->create(['name' => 'Bin Stock Category', 'code' => 'PSC001', 'status' => true]);
    $product = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'Mouse', 'code' => 'PST001', 'is_active' => true]);

    return compact('bin', 'product');
}

it('redirects guests away from bin stocks list', function (): void { get('/admin/bin-stocks')->assertRedirect('/admin/login'); });
it('redirects guests away from create bin stock page', function (): void { get('/admin/bin-stocks/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a bin stock', function (): void {
    actingAs(makeBinStocksAdminUser());
    Livewire::test(CreateBinStock::class)
        ->fillForm(['bin_id' => null, 'product_id' => null, 'product_variant_id' => null, 'quantity' => null])
        ->call('create')
        ->assertHasFormErrors(['bin_id' => 'required', 'product_id' => 'required', 'quantity' => 'required']);
});

it('performs bin stock crud operations', function (): void {
    actingAs(makeBinStocksAdminUser());
    ['bin' => $bin, 'product' => $product] = createBinStockSetup();

    Livewire::test(CreateBinStock::class)
        ->fillForm(['bin_id' => $bin->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => null, 'quantity' => 10])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BinStock::query()->firstOrFail();
    Livewire::test(ListBinStocks::class)->assertSuccessful()->assertSee('10');

    Livewire::test(EditBinStock::class, ['record' => $record->getKey()])
        ->fillForm(['bin_id' => $bin->getKey(), 'product_id' => $product->getKey(), 'product_variant_id' => null, 'quantity' => 15])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(15.0);

    Livewire::test(EditBinStock::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BinStock::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
