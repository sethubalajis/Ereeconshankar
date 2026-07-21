<?php

use App\Filament\Resources\StockReservations\Pages\CreateStockReservation;
use App\Filament\Resources\StockReservations\Pages\EditStockReservation;
use App\Filament\Resources\StockReservations\Pages\ListStockReservations;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockReservation;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeStockReservationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createStockReservationsSetup(): array
{
    $company = Company::query()->create(['name' => 'SR Co', 'code' => 'SRCO1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'SR Branch', 'code' => 'SRB01', 'status' => true]);
    $product = Product::query()->create(['name' => 'SR Product', 'code' => 'SRP01', 'is_active' => true]);
    $variant = ProductVariant::query()->create(['product_id' => $product->getKey(), 'sku' => 'SR-SKU-1', 'cost_price' => 9, 'selling_price' => 11, 'mrp' => 13, 'is_active' => true]);

    return compact('branch', 'product', 'variant');
}

it('redirects guests away from stock reservations list', function (): void {
    get('/admin/stock-reservations')->assertRedirect('/admin/login');
});

it('redirects guests away from create stock reservation page', function (): void {
    get('/admin/stock-reservations/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a stock reservation', function (): void {
    actingAs(makeStockReservationsAdminUser());

    Livewire::test(CreateStockReservation::class)
        ->fillForm([
            'product_id' => null,
            'branch_id' => null,
            'quantity' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'product_id' => 'required',
            'branch_id' => 'required',
            'quantity' => 'required',
            'status' => 'required',
        ]);
});

it('performs stock reservation crud operations', function (): void {
    actingAs(makeStockReservationsAdminUser());
    ['branch' => $branch, 'product' => $product, 'variant' => $variant] = createStockReservationsSetup();

    Livewire::test(CreateStockReservation::class)
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'quantity' => 4,
            'reference_id' => 1001,
            'status' => 'active',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = StockReservation::query()->firstOrFail();

    Livewire::test(ListStockReservations::class)
        ->assertSuccessful()
        ->assertSee('SR Product');

    Livewire::test(EditStockReservation::class, ['record' => $record->getKey()])
        ->fillForm([
            'product_id' => $product->getKey(),
            'product_variant_id' => $variant->getKey(),
            'branch_id' => $branch->getKey(),
            'quantity' => 6,
            'reference_id' => 1002,
            'status' => 'released',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(6.0);

    Livewire::test(EditStockReservation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(StockReservation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
