<?php

use App\Filament\Resources\Quotations\Pages\CreateQuotation;
use App\Filament\Resources\Quotations\Pages\EditQuotation;
use App\Filament\Resources\Quotations\Pages\ListQuotations;
use App\Models\Quotation;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeQuotationsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createQuotationVendor(): Vendor
{
    return Vendor::query()->create(['name' => 'Quote Vendor', 'code' => 'QTV01', 'status' => true]);
}

it('redirects guests away from quotations list', function (): void {
    get('/admin/quotations')->assertRedirect('/admin/login');
});

it('redirects guests away from create quotation page', function (): void {
    get('/admin/quotations/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a quotation', function (): void {
    actingAs(makeQuotationsAdminUser());

    Livewire::test(CreateQuotation::class)
        ->fillForm(['vendor_id' => null, 'quote_date' => null, 'total_amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['vendor_id' => 'required', 'quote_date' => 'required', 'total_amount' => 'required', 'status' => 'required']);
});

it('performs quotation crud operations', function (): void {
    actingAs(makeQuotationsAdminUser());
    $vendor = createQuotationVendor();

    Livewire::test(CreateQuotation::class)
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'quote_date' => '2026-07-21',
            'validity_date' => '2026-08-21',
            'total_amount' => 25000,
            'status' => 'received',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Quotation::query()->firstOrFail();

    Livewire::test(ListQuotations::class)
        ->assertSuccessful()
        ->assertSee('Quote Vendor');

    Livewire::test(EditQuotation::class, ['record' => $record->getKey()])
        ->fillForm([
            'vendor_id' => $vendor->getKey(),
            'quote_date' => '2026-07-22',
            'validity_date' => '2026-08-30',
            'total_amount' => 26000,
            'status' => 'accepted',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('accepted');

    Livewire::test(EditQuotation::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Quotation::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
