<?php

use App\Filament\Resources\PurchaseRequisitions\Pages\CreatePurchaseRequisition;
use App\Filament\Resources\PurchaseRequisitions\Pages\EditPurchaseRequisition;
use App\Filament\Resources\PurchaseRequisitions\Pages\ListPurchaseRequisitions;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PurchaseRequisition;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePurchaseRequisitionsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createPurchaseRequisitionBranch(): Branch
{
    $company = Company::query()->create(['name' => 'REQ Company', 'code' => 'RQC01', 'status' => true]);

    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'REQ Branch', 'code' => 'RQB01', 'status' => true]);
}

it('redirects guests away from purchase requisitions list', function (): void {
    get('/admin/purchase-requisitions')->assertRedirect('/admin/login');
});

it('redirects guests away from create purchase requisition page', function (): void {
    get('/admin/purchase-requisitions/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a purchase requisition', function (): void {
    actingAs(makePurchaseRequisitionsAdminUser());

    Livewire::test(CreatePurchaseRequisition::class)
        ->fillForm(['branch_id' => null, 'required_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'required_date' => 'required', 'status' => 'required']);
});

it('performs purchase requisition crud operations', function (): void {
    actingAs(makePurchaseRequisitionsAdminUser());
    $branch = createPurchaseRequisitionBranch();

    Livewire::test(CreatePurchaseRequisition::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'subject' => 'Office Supplies Request',
            'required_date' => '2026-07-28',
            'status' => 'pending',
            'remarks' => 'Need stationary items',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PurchaseRequisition::query()->firstOrFail();

    Livewire::test(ListPurchaseRequisitions::class)
        ->assertSuccessful()
        ->assertSee('pending');

    Livewire::test(EditPurchaseRequisition::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'subject' => 'IT Equipment Purchase',
            'required_date' => '2026-07-30',
            'status' => 'approved',
            'remarks' => 'Approved by manager',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditPurchaseRequisition::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PurchaseRequisition::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
