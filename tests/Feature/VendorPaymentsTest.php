<?php

use App\Filament\Resources\VendorPayments\Pages\CreateVendorPayment;
use App\Filament\Resources\VendorPayments\Pages\EditVendorPayment;
use App\Filament\Resources\VendorPayments\Pages\ListVendorPayments;
use App\Models\PaymentMode;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorPaymentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createVendorPaymentSetup(): array
{
    $vendor = Vendor::query()->create(['name' => 'VP Vendor', 'code' => 'VPV01', 'status' => true]);
    $mode = PaymentMode::query()->create(['name' => 'Bank Transfer', 'code' => 'BT', 'is_active' => true]);
    return compact('vendor', 'mode');
}

it('redirects guests away from vendor payments list', function (): void {
    get('/admin/vendor-payments')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor payment page', function (): void {
    get('/admin/vendor-payments/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor payment', function (): void {
    actingAs(makeVendorPaymentsAdminUser());

    Livewire::test(CreateVendorPayment::class)
        ->fillForm(['vendor_id' => null, 'payment_date' => null, 'amount' => null, 'payment_mode_id' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'payment_date' => 'required', 'amount' => 'required', 'payment_mode_id' => 'required', 'status' => 'required']);
});

it('performs vendor payment crud operations', function (): void {
    actingAs(makeVendorPaymentsAdminUser());
    ['vendor' => $vendor, 'mode' => $mode] = createVendorPaymentSetup();

    Livewire::test(CreateVendorPayment::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'purchase_order_id' => null,
            'payment_date' => '2026-07-21',
            'amount' => 5000,
            'payment_mode_id' => $mode->getKey(),
            'transaction_reference' => 'VP-REF-1',
            'status' => 'completed',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorPayment::query()->firstOrFail();
    Livewire::test(ListVendorPayments::class)->assertSuccessful()->assertSee('VP-REF-1');

    Livewire::test(EditVendorPayment::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'purchase_order_id' => null,
            'payment_date' => '2026-07-22',
            'amount' => 6000,
            'payment_mode_id' => $mode->getKey(),
            'transaction_reference' => 'VP-REF-2',
            'status' => 'pending',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(6000.0);

    Livewire::test(EditVendorPayment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorPayment::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
