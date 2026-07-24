<?php

use App\Filament\Resources\ComplaintTickets\Pages\CreateComplaintTicket;
use App\Filament\Resources\ComplaintTickets\Pages\EditComplaintTicket;
use App\Filament\Resources\ComplaintTickets\Pages\ListComplaintTickets;
use App\Models\ComplaintCategory;
use App\Models\ComplaintTicket;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeComplaintTicketsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createComplaintTicketCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Retail', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create(['customer_group_id' => $group->getKey(), 'name' => 'John Customer', 'phone' => '9000000001', 'total_points' => 0, 'wallet_balance' => 0, 'status' => true]);
}

function createComplaintTicketCategory(): ComplaintCategory
{
    return ComplaintCategory::query()->create(['name' => 'Delivery Issue', 'status' => true]);
}

it('redirects guests away from complaint tickets list', function (): void { get('/admin/complaint-tickets')->assertRedirect('/admin/login'); });
it('redirects guests away from create complaint ticket page', function (): void { get('/admin/complaint-tickets/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a complaint ticket', function (): void {
    actingAs(makeComplaintTicketsAdminUser());
    Livewire::test(CreateComplaintTicket::class)
    ->fillForm(['customer_id' => null, 'complaint_category_id' => null, 'description' => '', 'status' => null])
        ->call('create')
    ->assertHasFormErrors(['customer_id' => 'required', 'complaint_category_id' => 'required', 'status' => 'required']);
});

it('performs complaint ticket crud operations', function (): void {
    actingAs(makeComplaintTicketsAdminUser());
    $customer = createComplaintTicketCustomer();
    $category = createComplaintTicketCategory();

    Livewire::test(CreateComplaintTicket::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'complaint_category_id' => $category->getKey(), 'description' => 'Late delivery', 'status' => 'open'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ComplaintTicket::query()->firstOrFail();
    Livewire::test(ListComplaintTickets::class)->assertSuccessful()->assertSee($record->ticket_number);

    Livewire::test(EditComplaintTicket::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'complaint_category_id' => $category->getKey(), 'ticket_number' => 'TCK-002', 'description' => 'Broken seal', 'status' => 'resolved'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->ticket_number)->toBe('TCK-002');

    Livewire::test(EditComplaintTicket::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ComplaintTicket::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
