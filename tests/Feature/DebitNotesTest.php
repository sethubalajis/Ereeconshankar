<?php

use App\Filament\Resources\DebitNotes\Pages\CreateDebitNote;
use App\Filament\Resources\DebitNotes\Pages\EditDebitNote;
use App\Filament\Resources\DebitNotes\Pages\ListDebitNotes;
use App\Models\Branch;
use App\Models\Company;
use App\Models\DebitNote;
use App\Models\PurchaseReturn;
use App\Models\Role;
use App\Models\User;
use App\Models\Vendor;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDebitNotesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createDebitNoteVendor(): Vendor
{
    return Vendor::query()->create(['name' => 'Vendor A', 'code' => 'VND001', 'status' => true]);
}

function createDebitNotePurchaseReturn(Vendor $vendor): PurchaseReturn
{
    $company = Company::query()->create(['name' => 'Debit Co', 'code' => 'DBC001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Debit Branch', 'code' => 'DBR001', 'status' => true]);

    return PurchaseReturn::query()->create(['vendor_id' => $vendor->getKey(), 'branch_id' => $branch->getKey(), 'return_date' => '2026-07-21', 'total_amount' => 1000, 'reason' => 'Damaged items', 'status' => 'pending']);
}

it('redirects guests away from debit notes list', function (): void { get('/admin/debit-notes')->assertRedirect('/admin/login'); });
it('redirects guests away from create debit note page', function (): void { get('/admin/debit-notes/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a debit note', function (): void {
    actingAs(makeDebitNotesAdminUser());
    Livewire::test(CreateDebitNote::class)
        ->fillForm(['purchase_return_id' => null, 'vendor_id' => null, 'note_number' => '', 'note_date' => null, 'amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['purchase_return_id' => 'required', 'vendor_id' => 'required', 'note_number' => 'required', 'note_date' => 'required', 'amount' => 'required', 'status' => 'required']);
});

it('performs debit note crud operations', function (): void {
    actingAs(makeDebitNotesAdminUser());
    $vendor = createDebitNoteVendor();
    $purchaseReturn = createDebitNotePurchaseReturn($vendor);

    Livewire::test(CreateDebitNote::class)
        ->fillForm(['purchase_return_id' => $purchaseReturn->getKey(), 'vendor_id' => $vendor->getKey(), 'note_number' => 'DN-001', 'note_date' => '2026-07-21', 'amount' => 750, 'status' => 'issued'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = DebitNote::query()->firstOrFail();
    Livewire::test(ListDebitNotes::class)->assertSuccessful()->assertSee('DN-001');

    Livewire::test(EditDebitNote::class, ['record' => $record->getKey()])
        ->fillForm(['purchase_return_id' => $purchaseReturn->getKey(), 'vendor_id' => $vendor->getKey(), 'note_number' => 'DN-002', 'note_date' => '2026-07-22', 'amount' => 900, 'status' => 'draft'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->note_number)->toBe('DN-002');

    Livewire::test(EditDebitNote::class, ['record' => $record->getKey()])->callAction('delete');
    expect(DebitNote::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
