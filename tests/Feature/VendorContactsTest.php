<?php

use App\Filament\Resources\VendorContacts\Pages\CreateVendorContact;
use App\Filament\Resources\VendorContacts\Pages\EditVendorContact;
use App\Filament\Resources\VendorContacts\Pages\ListVendorContacts;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorContact;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorContactsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createVendorForContacts(): Vendor
{
    return Vendor::query()->create(['name' => 'VC Vendor', 'code' => 'VCV01', 'status' => true]);
}

it('redirects guests away from vendor contacts list', function (): void {
    get('/admin/vendor-contacts')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor contact page', function (): void {
    get('/admin/vendor-contacts/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor contact', function (): void {
    actingAs(makeVendorContactsAdminUser());

    Livewire::test(CreateVendorContact::class)
        ->fillForm(['vendor_id' => null, 'name' => '', 'is_primary' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'name' => 'required', 'is_primary' => 'required']);
});

it('performs vendor contact crud operations', function (): void {
    actingAs(makeVendorContactsAdminUser());
    $vendor = createVendorForContacts();

    Livewire::test(CreateVendorContact::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'name' => 'Primary Contact',
            'email' => 'primary@vendor.test',
            'phone' => '9000003001',
            'designation' => 'Manager',
            'is_primary' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorContact::query()->firstOrFail();
    Livewire::test(ListVendorContacts::class)->assertSuccessful()->assertSee('Primary Contact');

    Livewire::test(EditVendorContact::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'name' => 'Secondary Contact',
            'email' => 'secondary@vendor.test',
            'phone' => '9000003002',
            'designation' => 'Executive',
            'is_primary' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((bool) $record->is_primary)->toBeFalse();

    Livewire::test(EditVendorContact::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorContact::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
