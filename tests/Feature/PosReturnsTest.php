<?php

use App\Filament\Resources\PosReturns\Pages\CreatePosReturn;
use App\Filament\Resources\PosReturns\Pages\EditPosReturn;
use App\Filament\Resources\PosReturns\Pages\ListPosReturns;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PosReturn;
use App\Models\PosSale;
use App\Models\PosSaleItem;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\Stock;
use App\Models\StockLedger;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosReturnsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosReturnSale(): PosSale
{
    $company = Company::query()->create(['name' => 'Return Co', 'code' => 'RET01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Return Branch', 'code' => 'REB01', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'Return Terminal', 'terminal_code' => 'RET1', 'status' => true]);

    $sale = PosSale::query()->create([
        'branch_id' => $branch->getKey(),
        'pos_terminal_id' => $terminal->getKey(),
        'invoice_number' => 'POS-RET-001',
        'sale_date' => '2026-07-21',
        'total_amount' => 2000,
        'tax_amount' => 200,
        'discount_amount' => 0,
        'net_amount' => 2200,
        'status' => 'completed',
    ]);

    $category = ProductCategory::query()->create([
        'name' => 'POS Return Cat',
        'code' => 'PRCAT1',
        'status' => true,
    ]);

    $productA = Product::query()->create([
        'product_category_id' => $category->getKey(),
        'name' => 'POS Return Product A',
        'code' => 'PRPA1',
        'is_active' => true,
    ]);

    $productB = Product::query()->create([
        'product_category_id' => $category->getKey(),
        'name' => 'POS Return Product B',
        'code' => 'PRPB1',
        'is_active' => true,
    ]);

    PosSaleItem::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'product_id' => $productA->getKey(),
        'product_variant_id' => null,
        'quantity' => 2,
        'unit_price' => 100,
        'discount' => 0,
        'total_price' => 200,
    ]);

    PosSaleItem::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'product_id' => $productB->getKey(),
        'product_variant_id' => null,
        'quantity' => 3,
        'unit_price' => 150,
        'discount' => 0,
        'total_price' => 450,
    ]);

    return $sale;
}

it('redirects guests away from pos returns list', function (): void { get('/admin/pos-returns')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos return page', function (): void { get('/admin/pos-returns/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos return', function (): void {
    actingAs(makePosReturnsAdminUser());

    Livewire::test(CreatePosReturn::class)
        ->fillForm(['pos_sale_id' => null, 'return_date' => null, 'return_amount' => null, 'reason' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['pos_sale_id' => 'required', 'return_date' => 'required', 'status' => 'required']);
});

it('performs pos return crud operations', function (): void {
    actingAs(makePosReturnsAdminUser());
    $sale = createPosReturnSale();
    $saleItems = $sale->posSaleItems()->with('product')->orderBy('id')->get();

    Livewire::test(CreatePosReturn::class)
        ->fillForm([
            'pos_sale_id' => $sale->getKey(),
            'return_date' => '2026-07-22',
            'reason' => 'Damaged item',
            'status' => 'completed',
            'return_items' => [
                [
                    'pos_sale_item_id' => $saleItems[0]->getKey(),
                    'product_name' => $saleItems[0]->product->name,
                    'variant_sku' => null,
                    'sold_quantity' => 2,
                    'return_quantity' => 2,
                    'unit_price' => 100,
                    'line_total' => 200,
                ],
                [
                    'pos_sale_item_id' => $saleItems[1]->getKey(),
                    'product_name' => $saleItems[1]->product->name,
                    'variant_sku' => null,
                    'sold_quantity' => 3,
                    'return_quantity' => 1,
                    'unit_price' => 150,
                    'line_total' => 150,
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosReturn::query()->firstOrFail();
    Livewire::test(ListPosReturns::class)->assertSuccessful()->assertSee('POS-RET-001');
    expect((float) $record->return_amount)->toBe(350.0);
    expect($record->return_items)->toBeArray();
    expect($record->return_items)->toHaveCount(2);

    Livewire::test(EditPosReturn::class, ['record' => $record->getKey()])
        ->fillForm([
            'pos_sale_id' => $sale->getKey(),
            'return_date' => '2026-07-23',
            'reason' => 'Partial return',
            'status' => 'pending',
            'return_items' => [
                [
                    'pos_sale_item_id' => $saleItems[0]->getKey(),
                    'product_name' => $saleItems[0]->product->name,
                    'variant_sku' => null,
                    'sold_quantity' => 2,
                    'return_quantity' => 1,
                    'unit_price' => 100,
                    'line_total' => 100,
                ],
                [
                    'pos_sale_item_id' => $saleItems[1]->getKey(),
                    'product_name' => $saleItems[1]->product->name,
                    'variant_sku' => null,
                    'sold_quantity' => 3,
                    'return_quantity' => 0,
                    'unit_price' => 150,
                    'line_total' => 0,
                ],
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->return_amount)->toBe(100.0);
    expect($record->return_items)->toHaveCount(1);

    Livewire::test(EditPosReturn::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosReturn::query()->whereKey($record->getKey())->exists())->toBeFalse();
});

it('adds returned quantities back to stock when pos return is completed', function (): void {
    $sale = createPosReturnSale();
    $saleItems = $sale->posSaleItems()->with('product')->orderBy('id')->get();

    $preStockA = Stock::query()
        ->where('product_id', $saleItems[0]->product_id)
        ->where('product_variant_id', $saleItems[0]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $preStockB = Stock::query()
        ->where('product_id', $saleItems[1]->product_id)
        ->where('product_variant_id', $saleItems[1]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $returnRecord = PosReturn::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'return_date' => '2026-07-22',
        'return_amount' => 400,
        'reason' => 'Customer return',
        'status' => 'completed',
        'return_items' => [
            [
                'pos_sale_item_id' => $saleItems[0]->getKey(),
                'product_name' => $saleItems[0]->product->name,
                'variant_sku' => null,
                'sold_quantity' => 2,
                'return_quantity' => 1,
                'unit_price' => 100,
                'line_total' => 100,
            ],
            [
                'pos_sale_item_id' => $saleItems[1]->getKey(),
                'product_name' => $saleItems[1]->product->name,
                'variant_sku' => null,
                'sold_quantity' => 3,
                'return_quantity' => 2,
                'unit_price' => 150,
                'line_total' => 300,
            ],
        ],
    ]);

    $postStockA = $preStockA->fresh();
    $postStockB = $preStockB->fresh();

    expect((float) $postStockA->quantity)->toBe((float) $preStockA->quantity + 1.0);
    expect((float) $postStockB->quantity)->toBe((float) $preStockB->quantity + 2.0);

    $referenceA = 'POS-RET-' . $returnRecord->id . '-' . $saleItems[0]->getKey();
    $referenceB = 'POS-RET-' . $returnRecord->id . '-' . $saleItems[1]->getKey();

    expect(StockTransaction::query()->where('reference', $referenceA)->where('reference_type', 'App\\Models\\PosReturn')->exists())->toBeTrue();
    expect(StockTransaction::query()->where('reference', $referenceB)->where('reference_type', 'App\\Models\\PosReturn')->exists())->toBeTrue();
});

it('reflects stock when pos return moves from pending to refunded', function (): void {
    $sale = createPosReturnSale();
    $saleItems = $sale->posSaleItems()->with('product')->orderBy('id')->get();

    $preStock = Stock::query()
        ->where('product_id', $saleItems[0]->product_id)
        ->where('product_variant_id', $saleItems[0]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $returnRecord = PosReturn::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'return_date' => '2026-07-22',
        'return_amount' => 100,
        'reason' => 'Pending approval',
        'status' => 'pending',
        'return_items' => [
            [
                'pos_sale_item_id' => $saleItems[0]->getKey(),
                'product_name' => $saleItems[0]->product->name,
                'variant_sku' => null,
                'sold_quantity' => 2,
                'return_quantity' => 1,
                'unit_price' => 100,
                'line_total' => 100,
            ],
        ],
    ]);

    expect((float) $preStock->fresh()->quantity)->toBe((float) $preStock->quantity);

    $returnRecord->update(['status' => 'refunded']);

    $postStock = $preStock->fresh();
    expect((float) $postStock->quantity)->toBe((float) $preStock->quantity + 1.0);

    $reference = 'POS-RET-' . $returnRecord->id . '-' . $saleItems[0]->getKey();
    expect(StockTransaction::query()->where('reference', $reference)->where('reference_type', 'App\\Models\\PosReturn')->exists())->toBeTrue();
});

it('uses default warehouse for pos return stock update without creating a new stock row', function (): void {
    $sale = createPosReturnSale();
    $saleItem = $sale->posSaleItems()->with('product')->orderBy('id')->firstOrFail();

    $existingStock = Stock::query()
        ->where('product_id', $saleItem->product_id)
        ->where('product_variant_id', $saleItem->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $defaultWarehouse = Warehouse::query()->create([
        'branch_id' => $sale->branch_id,
        'name' => 'Return Default Warehouse',
        'code' => 'RDW1',
        'is_default' => true,
        'status' => true,
    ]);

    PosReturn::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'return_date' => '2026-07-23',
        'return_amount' => 100,
        'reason' => 'Default warehouse return update',
        'status' => 'completed',
        'return_items' => [
            [
                'pos_sale_item_id' => $saleItem->getKey(),
                'product_name' => $saleItem->product->name,
                'variant_sku' => null,
                'sold_quantity' => 2,
                'return_quantity' => 1,
                'unit_price' => 100,
                'line_total' => 100,
            ],
        ],
    ]);

    $stockRows = Stock::query()
        ->where('product_id', $saleItem->product_id)
        ->where('product_variant_id', $saleItem->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->get();

    $updatedStock = $existingStock->fresh();
    expect($stockRows)->toHaveCount(1);
    expect($updatedStock->warehouse_id)->toBe($defaultWarehouse->getKey());
    expect((float) $updatedStock->quantity)->toBe(-1.0);
});

it('still reflects stock when return payload is missing pos_sale_item_id', function (): void {
    $sale = createPosReturnSale();
    $saleItems = $sale->posSaleItems()->with('product')->orderBy('id')->get();

    $preStockA = Stock::query()
        ->where('product_id', $saleItems[0]->product_id)
        ->where('product_variant_id', $saleItems[0]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $preStockB = Stock::query()
        ->where('product_id', $saleItems[1]->product_id)
        ->where('product_variant_id', $saleItems[1]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->whereNull('warehouse_id')
        ->firstOrFail();

    $return = PosReturn::query()->create([
        'pos_sale_id' => $sale->getKey(),
        'return_date' => '2026-07-24',
        'return_amount' => 250,
        'reason' => 'Missing id payload test',
        'status' => 'completed',
        'return_items' => [
            [
                'product_name' => $saleItems[0]->product->name,
                'variant_sku' => null,
                'sold_quantity' => 2,
                'return_quantity' => 1,
                'unit_price' => 100,
                'line_total' => 100,
            ],
            [
                'product_name' => $saleItems[1]->product->name,
                'variant_sku' => null,
                'sold_quantity' => 3,
                'return_quantity' => 1,
                'unit_price' => 150,
                'line_total' => 150,
            ],
        ],
    ]);

    expect((float) $preStockA->fresh()->quantity)->toBe((float) $preStockA->quantity + 1.0);
    expect((float) $preStockB->fresh()->quantity)->toBe((float) $preStockB->quantity + 1.0);

    $referenceA = 'POS-RET-' . $return->id . '-' . $saleItems[0]->getKey();
    $referenceB = 'POS-RET-' . $return->id . '-' . $saleItems[1]->getKey();
    expect(StockTransaction::query()->where('reference', $referenceA)->where('reference_type', 'App\\Models\\PosReturn')->exists())->toBeTrue();
    expect(StockTransaction::query()->where('reference', $referenceB)->where('reference_type', 'App\\Models\\PosReturn')->exists())->toBeTrue();

    $ledgerA = StockLedger::query()
        ->where('product_id', $saleItems[0]->product_id)
        ->where('product_variant_id', $saleItems[0]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->latest('id')
        ->first();

    $ledgerB = StockLedger::query()
        ->where('product_id', $saleItems[1]->product_id)
        ->where('product_variant_id', $saleItems[1]->product_variant_id)
        ->where('branch_id', $sale->branch_id)
        ->latest('id')
        ->first();

    expect($ledgerA)->not->toBeNull();
    expect($ledgerB)->not->toBeNull();
    expect((float) $ledgerA->inward_qty)->toBe(1.0);
    expect((float) $ledgerB->inward_qty)->toBe(1.0);
});
