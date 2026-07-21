<?php

use App\Filament\Resources\EInvoices\Pages\CreateEInvoice;
use App\Filament\Resources\EInvoices\Pages\EditEInvoice;
use App\Filament\Resources\EInvoices\Pages\ListEInvoices;
use App\Models\EInvoice;
use App\Models\Role;
use App\Models\TaxInvoice;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeEInvoicesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createEInvoiceTaxInvoice(): TaxInvoice
{
    return TaxInvoice::query()->create([
        'invoiceable_id' => 1,
        'invoiceable_type' => 'App\\Models\\PosSale',
        'invoice_number' => 'TINV-001',
        'invoice_date' => '2026-07-21',
        'taxable_amount' => 1000,
        'tax_amount' => 180,
        'total_amount' => 1180,
    ]);
}

it('redirects guests away from e invoices list', function (): void { get('/admin/e-invoices')->assertRedirect('/admin/login'); });
it('redirects guests away from create e invoice page', function (): void { get('/admin/e-invoices/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating an e invoice', function (): void {
    actingAs(makeEInvoicesAdminUser());
    Livewire::test(CreateEInvoice::class)
        ->fillForm(['tax_invoice_id' => null, 'irn' => '', 'ack_no' => '', 'ack_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['tax_invoice_id' => 'required', 'irn' => 'required', 'status' => 'required']);
});

it('performs e invoice crud operations', function (): void {
    actingAs(makeEInvoicesAdminUser());
    $taxInvoice = createEInvoiceTaxInvoice();

    Livewire::test(CreateEInvoice::class)
        ->fillForm(['tax_invoice_id' => $taxInvoice->getKey(), 'irn' => 'IRN001', 'ack_no' => 'ACK001', 'ack_date' => '2026-07-21', 'status' => 'generated'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = EInvoice::query()->firstOrFail();
    Livewire::test(ListEInvoices::class)->assertSuccessful()->assertSee('IRN001');

    Livewire::test(EditEInvoice::class, ['record' => $record->getKey()])
        ->fillForm(['tax_invoice_id' => $taxInvoice->getKey(), 'irn' => 'IRN002', 'ack_no' => 'ACK002', 'ack_date' => '2026-07-22', 'status' => 'cancelled'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->irn)->toBe('IRN002');

    Livewire::test(EditEInvoice::class, ['record' => $record->getKey()])->callAction('delete');
    expect(EInvoice::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
