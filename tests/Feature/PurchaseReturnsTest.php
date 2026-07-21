<?php

use App\Filament\Resources\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\EditPurchaseReturn;
use App\Filament\Resources\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePurchaseReturnsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createPurchaseReturnSetup(): array
{
    $company = Company::query()->create(['name' => 'RET Company', 'code' => 'RETC1', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'RET Branch', 'code' => 'RETB1', 'status' => true]);
    $vendor = Vendor::query()->create(['name' => 'RET Vendor', 'code' => 'RETV1', 'status' => true]);
    $purchaseOrder = PurchaseOrder::query()->create([
        'vendor_id' => $vendor->getKey(),
        'branch_id' => $branch->getKey(),
        'po_date' => '2026-07-20',
        'expected_delivery_date' => '2026-07-28',
        'total_amount' => 300,
        'status' => 'approved',
    ]);

    return compact('branch', 'vendor', 'purchaseOrder');
}

it('redirects guests away from purchase returns list', function (): void {
    get('/admin/purchase-returns')->assertRedirect('/admin/login');
});

it('redirects guests away from create purchase return page', function (): void {
    get('/admin/purchase-returns/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a purchase return', function (): void {
    actingAs(makePurchaseReturnsAdminUser());

    Livewire::test(CreatePurchaseReturn::class)
        ->fillForm([
            'vendor_id' => null,
            'branch_id' => null,
            'return_date' => null,
            'total_amount' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'vendor_id' => 'required',
            'branch_id' => 'required',
            'return_date' => 'required',
            'total_amount' => 'required',
            'status' => 'required',
        ]);
});

it('performs purchase return crud operations', function (): void {
    actingAs(makePurchaseReturnsAdminUser());
    ['branch' => $branch, 'vendor' => $vendor, 'purchaseOrder' => $purchaseOrder] = createPurchaseReturnSetup();

    Livewire::test(CreatePurchaseReturn::class)
        ->fillForm([
            'purchase_order_id' => $purchaseOrder->getKey(),
            'vendor_id' => $vendor->getKey(),
            'branch_id' => $branch->getKey(),
            'return_date' => '2026-07-22',
            'total_amount' => 150,
            'reason' => 'Damaged materials',
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PurchaseReturn::query()->firstOrFail();

    Livewire::test(ListPurchaseReturns::class)
        ->assertSuccessful()
        ->assertSee('RET Vendor');

    Livewire::test(EditPurchaseReturn::class, ['record' => $record->getKey()])
        ->fillForm([
            'purchase_order_id' => $purchaseOrder->getKey(),
            'vendor_id' => $vendor->getKey(),
            'branch_id' => $branch->getKey(),
            'return_date' => '2026-07-23',
            'total_amount' => 100,
            'reason' => 'Partial return',
            'status' => 'approved',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditPurchaseReturn::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PurchaseReturn::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
