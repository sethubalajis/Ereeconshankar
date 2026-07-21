<?php

use App\Filament\Resources\EWayBills\Pages\CreateEWayBill;
use App\Filament\Resources\EWayBills\Pages\EditEWayBill;
use App\Filament\Resources\EWayBills\Pages\ListEWayBills;
use App\Models\EWayBill;
use App\Models\Role;
use App\Models\TaxInvoice;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEWayBillsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEWayTaxInvoice(): TaxInvoice
{
    return TaxInvoice::query()->create([
        'invoiceable_id' => 1,
        'invoiceable_type' => 'App\\Models\\PosSale',
        'invoice_number' => 'TINV-EWB-001',
        'invoice_date' => '2026-07-21',
        'taxable_amount' => 1000,
        'tax_amount' => 180,
        'total_amount' => 1180,
    ]);
}

it('redirects guests away from e way bills list', function (): void { get('/admin/e-way-bills')->assertRedirect('/admin/login'); });
it('redirects guests away from create e way bill page', function (): void { get('/admin/e-way-bills/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an e way bill', function (): void {
    actingAs(makeEWayBillsAdminUser());
    Livewire::test(CreateEWayBill::class)
        ->fillForm(['tax_invoice_id' => null, 'eway_bill_no' => '', 'valid_until' => null, 'transporter_id' => null, 'vehicle_no' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['tax_invoice_id' => 'required', 'eway_bill_no' => 'required', 'status' => 'required']);
});

it('performs e way bill crud operations', function (): void {
    actingAs(makeEWayBillsAdminUser());
    $taxInvoice = createEWayTaxInvoice();

    Livewire::test(CreateEWayBill::class)
        ->fillForm(['tax_invoice_id' => $taxInvoice->getKey(), 'eway_bill_no' => 'EWB001', 'valid_until' => '2026-07-30', 'transporter_id' => 123, 'vehicle_no' => 'TN01AB1234', 'status' => 'active'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EWayBill::query()->firstOrFail();
    Livewire::test(ListEWayBills::class)->assertSuccessful()->assertSee('EWB001');

    Livewire::test(EditEWayBill::class, ['record' => $record->getKey()])
        ->fillForm(['tax_invoice_id' => $taxInvoice->getKey(), 'eway_bill_no' => 'EWB002', 'valid_until' => '2026-08-15', 'transporter_id' => 456, 'vehicle_no' => 'TN02CD5678', 'status' => 'cancelled'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->eway_bill_no)->toBe('EWB002');

    Livewire::test(EditEWayBill::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EWayBill::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
