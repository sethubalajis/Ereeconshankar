<?php

use App\Filament\Resources\VendorAgreements\Pages\CreateVendorAgreement;
use App\Filament\Resources\VendorAgreements\Pages\EditVendorAgreement;
use App\Filament\Resources\VendorAgreements\Pages\ListVendorAgreements;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorAgreement;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorAgreementsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createVendorForAgreements(): Vendor
{
    return Vendor::query()->create(['name' => 'VA Vendor', 'code' => 'VAV01', 'status' => true]);
}

it('redirects guests away from vendor agreements list', function (): void {
    get('/admin/vendor-agreements')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor agreement page', function (): void {
    get('/admin/vendor-agreements/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor agreement', function (): void {
    actingAs(makeVendorAgreementsAdminUser());

    Livewire::test(CreateVendorAgreement::class)
        ->fillForm(['vendor_id' => null, 'title' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'title' => 'required', 'status' => 'required']);
});

it('performs vendor agreement crud operations', function (): void {
    actingAs(makeVendorAgreementsAdminUser());
    $vendor = createVendorForAgreements();

    Livewire::test(CreateVendorAgreement::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'title' => 'Master Service Agreement (MSA)',
            'start_date' => '2026-07-21',
            'end_date' => '2027-07-20',
            'status' => 'active',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorAgreement::query()->firstOrFail();
    Livewire::test(ListVendorAgreements::class)->assertSuccessful()->assertSee('MSA');

    Livewire::test(EditVendorAgreement::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'title' => 'Service Level Agreement (SLA)',
            'start_date' => '2026-08-01',
            'end_date' => '2027-08-01',
            'status' => 'pending',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('pending');

    Livewire::test(EditVendorAgreement::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorAgreement::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
