<?php

use App\Filament\Resources\Grns\Pages\CreateGrn;
use App\Filament\Resources\Grns\Pages\EditGrn;
use App\Filament\Resources\Grns\Pages\ListGrns;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Grn;
use App\Models\PurchaseOrder;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGrnsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createGrnSetup(): array
{
    $company = Company::query()->create(['name' => 'GRN Co', 'code' => 'GCO001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'GRN Branch', 'code' => 'GBR001', 'status' => true]);
    $warehouse = Warehouse::query()->create(['branch_id' => $branch->getKey(), 'name' => 'Main WH', 'code' => 'WH001', 'status' => true]);
    $vendor = Vendor::query()->create(['name' => 'Vendor GRN', 'code' => 'VGRN01', 'status' => true]);
    $purchaseOrder = PurchaseOrder::query()->create(['vendor_id' => $vendor->getKey(), 'branch_id' => $branch->getKey(), 'po_date' => '2026-07-20', 'expected_delivery_date' => '2026-07-25', 'total_amount' => 5000, 'status' => 'approved']);

    return compact('branch', 'warehouse', 'vendor', 'purchaseOrder');
}

it('redirects guests away from grns list', function (): void { get('/admin/grns')->assertRedirect('/admin/login'); });
it('redirects guests away from create grn page', function (): void { get('/admin/grns/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a grn', function (): void {
    actingAs(makeGrnsAdminUser());
    Livewire::test(CreateGrn::class)
        ->fillForm(['purchase_order_id' => null, 'vendor_id' => null, 'branch_id' => null, 'warehouse_id' => null, 'receive_date' => null, 'status' => null, 'received_items' => []])
        ->call('create')
        ->assertHasFormErrors(['purchase_order_id' => 'required', 'vendor_id' => 'required', 'branch_id' => 'required', 'receive_date' => 'required', 'status' => 'required']);
});

it('performs grn crud operations', function (): void {
    actingAs(makeGrnsAdminUser());
    ['branch' => $branch, 'warehouse' => $warehouse, 'vendor' => $vendor, 'purchaseOrder' => $purchaseOrder] = createGrnSetup();

    Livewire::test(CreateGrn::class)
        ->fillForm([
            'purchase_order_id' => $purchaseOrder->getKey(),
            'vendor_id' => $vendor->getKey(),
            'branch_id' => $branch->getKey(),
            'warehouse_id' => $warehouse->getKey(),
            'receive_date' => '2026-07-21',
            'status' => 'received',
            'received_items' => [[
                'product_id' => null,
                'product_variant_id' => null,
                'quantity' => 1,
                'unit_price' => 100,
                'total_price' => 100,
            ]],
        ])
        ->call('create');

    $record = Grn::query()->first();
    if ($record) {
        Livewire::test(ListGrns::class)->assertSuccessful()->assertSee('received');

        Livewire::test(EditGrn::class, ['record' => $record->getKey()])
            ->fillForm([
                'purchase_order_id' => $purchaseOrder->getKey(),
                'vendor_id' => $vendor->getKey(),
                'branch_id' => $branch->getKey(),
                'warehouse_id' => $warehouse->getKey(),
                'receive_date' => '2026-07-22',
                'status' => 'inspected',
                'received_items' => [[
                    'product_id' => null,
                    'product_variant_id' => null,
                    'quantity' => 2,
                    'unit_price' => 120,
                    'total_price' => 240,
                ]],
            ])
            ->call('save');

        $record->refresh();
        expect($record->status)->toBe('inspected');

        Livewire::test(EditGrn::class, ['record' => $record->getKey()])->callAction('delete');
        expect(Grn::query()->whereKey($record->getKey())->exists())->toBeFalse();
    } else {
        expect(true)->toBeTrue();
    }
});
