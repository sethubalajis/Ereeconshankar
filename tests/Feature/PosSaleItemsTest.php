<?php

use App\Filament\Resources\PosSaleItems\Pages\CreatePosSaleItem;
use App\Filament\Resources\PosSaleItems\Pages\EditPosSaleItem;
use App\Filament\Resources\PosSaleItems\Pages\ListPosSaleItems;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\PosTerminal;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosSaleItemsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosSaleItemSetup(): array
{
    $company = Company::query()->create(['name' => 'PSI Co', 'code' => 'PSI01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'PSI Branch', 'code' => 'PSIB1', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'PSI Terminal', 'terminal_code' => 'PSIT1', 'status' => true]);
    $sale = PosSale::query()->create([
        'branch_id' => $branch->getKey(),
        'pos_terminal_id' => $terminal->getKey(),
        'invoice_number' => 'POS-PSI-001',
        'sale_date' => '2026-07-21',
        'total_amount' => 1000,
        'tax_amount' => 100,
        'discount_amount' => 0,
        'net_amount' => 1100,
        'status' => 'completed',
    ]);

    $category = ProductCategory::query()->create(['name' => 'POS Category', 'code' => 'PCAT1', 'status' => true]);
    $product = Product::query()->create(['product_category_id' => $category->getKey(), 'name' => 'POS Product', 'code' => 'PPRO1', 'is_active' => true]);

    return compact('sale', 'product');
}

it('redirects guests away from pos sale items list', function (): void { get('/admin/pos-sale-items')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos sale item page', function (): void { get('/admin/pos-sale-items/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos sale item', function (): void {
    actingAs(makePosSaleItemsAdminUser());

    Livewire::test(CreatePosSaleItem::class)
        ->fillForm(['pos_sale_id' => null, 'product_id' => null, 'quantity' => null, 'unit_price' => null, 'discount' => null, 'total_price' => null])
        ->call('create')
        ->assertHasFormErrors(['pos_sale_id' => 'required', 'product_id' => 'required', 'quantity' => 'required', 'unit_price' => 'required', 'discount' => 'required', 'total_price' => 'required']);
});

it('performs pos sale item crud operations', function (): void {
    actingAs(makePosSaleItemsAdminUser());
    ['sale' => $sale, 'product' => $product] = createPosSaleItemSetup();

    Livewire::test(CreatePosSaleItem::class)
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'product_id' => $product->getKey(), 'quantity' => 2, 'unit_price' => 500, 'discount' => 50, 'total_price' => 950])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosSaleItem::query()->firstOrFail();
    Livewire::test(ListPosSaleItems::class)->assertSuccessful()->assertSee('POS Product');

    Livewire::test(EditPosSaleItem::class, ['record' => $record->getKey()])
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'product_id' => $product->getKey(), 'quantity' => 3, 'unit_price' => 500, 'discount' => 0, 'total_price' => 1500])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->quantity)->toBe(3.0);

    Livewire::test(EditPosSaleItem::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosSaleItem::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
