<?php

use App\Filament\Resources\VendorPerformances\Pages\CreateVendorPerformance;
use App\Filament\Resources\VendorPerformances\Pages\EditVendorPerformance;
use App\Filament\Resources\VendorPerformances\Pages\ListVendorPerformances;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorPerformance;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeVendorPerformancesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createVendorForPerformance(): Vendor
{
    return Vendor::query()->create(['name' => 'VPerf Vendor', 'code' => 'VPF01', 'status' => true]);
}

it('redirects guests away from vendor performances list', function (): void {
    get('/admin/vendor-performances')->assertRedirect('/admin/login');
});

it('redirects guests away from create vendor performance page', function (): void {
    get('/admin/vendor-performances/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a vendor performance', function (): void {
    actingAs(makeVendorPerformancesAdminUser());

    Livewire::test(CreateVendorPerformance::class)
        ->fillForm(['vendor_id' => null, 'review_date' => null, 'rating' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'review_date' => 'required', 'rating' => 'required']);
});

it('performs vendor performance crud operations', function (): void {
    actingAs(makeVendorPerformancesAdminUser());
    $vendor = createVendorForPerformance();

    Livewire::test(CreateVendorPerformance::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'review_date' => '2026-07-21',
            'rating' => 4.5,
            'delivery_score' => 90,
            'quality_score' => 88,
            'remarks' => 'Good performance',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = VendorPerformance::query()->firstOrFail();
    Livewire::test(ListVendorPerformances::class)->assertSuccessful()->assertSee('VPerf Vendor');

    Livewire::test(EditVendorPerformance::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'review_date' => '2026-07-22',
            'rating' => 4.8,
            'delivery_score' => 95,
            'quality_score' => 92,
            'remarks' => 'Excellent performance',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->rating)->toBe(4.8);

    Livewire::test(EditVendorPerformance::class, ['record' => $record->getKey()])->callAction('delete');
    expect(VendorPerformance::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
