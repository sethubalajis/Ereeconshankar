<?php

use App\Filament\Resources\TransitInventories\Pages\CreateTransitInventory;
use App\Filament\Resources\TransitInventories\Pages\EditTransitInventory;
use App\Filament\Resources\TransitInventories\Pages\ListTransitInventories;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockTransfer;
use App\Models\TransitInventory;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTransitInventoriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTransitInventorySetup(): array
{
    $company = Company::query()->create(['name' => 'TIv Co', 'code' => 'TIVC1', 'status' => true]);
    $from = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TIv From', 'code' => 'TIVF1', 'status' => true]);
    $to = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TIv To', 'code' => 'TIVT1', 'status' => true]);
    $transfer = StockTransfer::query()->create([
        'from_branch_id' => $from->getKey(),
        'to_branch_id' => $to->getKey(),
        'transfer_date' => '2026-07-21',
        'vehicle_number' => 'TN99AA0099',
        'status' => 'in_transit',
    ]);
    $product = Product::query()->create(['name' => 'TIv Product', 'code' => 'TIVP1', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'TIV-SKU-1', 'cost_price' => 6, 'selling_price' => 8, 'mrp' => 10, 'is_active' => true]);

    return compact('transfer', 'product', 'variant');
}

it('redirects guests away from transit inventories list', function (): void {
    get('/admin/transit-inventories')->assertRedirect('/admin/login');
});

it('redirects guests away from create transit inventory page', function (): void {
    get('/admin/transit-inventories/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a transit inventory', function (): void {
    actingAs(makeTransitInventoriesAdminUser());

    Livewire::test(CreateTransitInventory::class)
        ->fillForm(['stock_transfer_id' => null, 'product_id' => null, 'quantity' => null])
        ->call('create')
        ->assertHasFormErrors(['stock_transfer_id' => 'required', 'product_id' => 'required', 'quantity' => 'required']);
});

it('performs transit inventory crud operations', function (): void {
    actingAs(makeTransitInventoriesAdminUser());
    ['transfer' => $transfer, 'product' => $product, 'variant' => $variant] = createTransitInventorySetup();

    Livewire::test(CreateTransitInventory::class)
        ->fillForm([
            'stock_transfer_id' => $transfer->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 12,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TransitInventory::query()->firstOrFail();
    Livewire::test(ListTransitInventories::class)->assertSuccessful()->assertSee('TIv Product');

    Livewire::test(EditTransitInventory::class, ['record' => $record->getKey()])
        ->fillForm([
            'stock_transfer_id' => $transfer->getKey(),
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'quantity' => 10,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(10.0);

    Livewire::test(EditTransitInventory::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TransitInventory::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
