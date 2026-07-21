<?php

use App\Filament\Resources\CreditNotes\Pages\CreateCreditNote;
use App\Filament\Resources\CreditNotes\Pages\EditCreditNote;
use App\Filament\Resources\CreditNotes\Pages\ListCreditNotes;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CreditNote;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCreditNotesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCreditNoteCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Retail', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create(['customer_group_id' => $group->getKey(), 'name' => 'Credit Customer', 'phone' => '9000000002', 'total_points' => 0, 'wallet_balance' => 0, 'status' => true]);
}

it('redirects guests away from credit notes list', function (): void { get('/admin/credit-notes')->assertRedirect('/admin/login'); });
it('redirects guests away from create credit note page', function (): void { get('/admin/credit-notes/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a credit note', function (): void {
    actingAs(makeCreditNotesAdminUser());
    Livewire::test(CreateCreditNote::class)
        ->fillForm(['customer_id' => null, 'note_number' => '', 'note_date' => null, 'amount' => null, 'reason' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['customer_id' => 'required', 'note_number' => 'required', 'note_date' => 'required', 'amount' => 'required', 'status' => 'required']);
});

it('performs credit note crud operations', function (): void {
    actingAs(makeCreditNotesAdminUser());
    $customer = createCreditNoteCustomer();

    Livewire::test(CreateCreditNote::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'note_number' => 'CN-001', 'note_date' => '2026-07-21', 'amount' => 250, 'reason' => 'Return adjustment', 'status' => 'issued'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CreditNote::query()->firstOrFail();
    Livewire::test(ListCreditNotes::class)->assertSuccessful()->assertSee('CN-001');

    Livewire::test(EditCreditNote::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'note_number' => 'CN-002', 'note_date' => '2026-07-22', 'amount' => 300, 'reason' => 'Revised adjustment', 'status' => 'draft'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->note_number)->toBe('CN-002');

    Livewire::test(EditCreditNote::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CreditNote::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
