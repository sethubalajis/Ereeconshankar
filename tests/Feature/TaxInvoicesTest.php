<?php

use App\Filament\Resources\TaxInvoices\Pages\CreateTaxInvoice;
use App\Filament\Resources\TaxInvoices\Pages\EditTaxInvoice;
use App\Filament\Resources\TaxInvoices\Pages\ListTaxInvoices;
use App\Models\Role;
use App\Models\TaxInvoice;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTaxInvoicesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from tax invoices list', function (): void {
    get('/admin/tax-invoices')->assertRedirect('/admin/login');
});

it('redirects guests away from create tax invoice page', function (): void {
    get('/admin/tax-invoices/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a tax invoice', function (): void {
    actingAs(makeTaxInvoicesAdminUser());

    Livewire::test(CreateTaxInvoice::class)
        ->fillForm([
            'invoiceable_id' => null,
            'invoiceable_type' => '',
            'invoice_number' => '',
            'invoice_date' => null,
            'taxable_amount' => null,
            'tax_amount' => null,
            'total_amount' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'invoiceable_id' => 'required',
            'invoiceable_type' => 'required',
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'taxable_amount' => 'required',
            'tax_amount' => 'required',
            'total_amount' => 'required',
        ]);
});

it('performs tax invoice crud operations', function (): void {
    actingAs(makeTaxInvoicesAdminUser());

    Livewire::test(CreateTaxInvoice::class)
        ->fillForm([
            'invoiceable_id' => 1,
            'invoiceable_type' => 'App\\Models\\Vendor',
            'invoice_number' => 'TAX-INV-001',
            'invoice_date' => '2026-07-21',
            'taxable_amount' => 10000,
            'tax_amount' => 1800,
            'total_amount' => 11800,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TaxInvoice::query()->firstOrFail();
    Livewire::test(ListTaxInvoices::class)->assertSuccessful()->assertSee('TAX-INV-001');

    Livewire::test(EditTaxInvoice::class, ['record' => $record->getKey()])
        ->fillForm([
            'invoiceable_id' => 2,
            'invoiceable_type' => 'App\\Models\\Customer',
            'invoice_number' => 'TAX-INV-002',
            'invoice_date' => '2026-07-22',
            'taxable_amount' => 15000,
            'tax_amount' => 2700,
            'total_amount' => 17700,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->invoice_number)->toBe('TAX-INV-002');

    Livewire::test(EditTaxInvoice::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TaxInvoice::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
