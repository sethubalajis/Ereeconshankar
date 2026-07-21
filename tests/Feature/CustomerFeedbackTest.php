<?php

use App\Filament\Resources\CustomerFeedback\Pages\CreateCustomerFeedback;
use App\Filament\Resources\CustomerFeedback\Pages\EditCustomerFeedback;
use App\Filament\Resources\CustomerFeedback\Pages\ListCustomerFeedback;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CustomerFeedback;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCustomerFeedbackAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCustomerFeedbackCustomer(): Customer
{
    $group = CustomerGroup::query()->create(['name' => 'Retail', 'discount_percentage' => 0, 'status' => true]);
    return Customer::query()->create(['customer_group_id' => $group->getKey(), 'name' => 'Feedback Customer', 'phone' => '9000000003', 'total_points' => 0, 'wallet_balance' => 0, 'status' => true]);
}

function createCustomerFeedbackBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Feedback Co', 'code' => 'FBC001', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Feedback Branch', 'code' => 'FBR001', 'status' => true]);
}

it('redirects guests away from customer feedback list', function (): void { get('/admin/customer-feedback')->assertRedirect('/admin/login'); });
it('redirects guests away from create customer feedback page', function (): void { get('/admin/customer-feedback/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating customer feedback', function (): void {
    actingAs(makeCustomerFeedbackAdminUser());
    Livewire::test(CreateCustomerFeedback::class)
        ->fillForm(['customer_id' => null, 'branch_id' => null, 'rating' => null, 'comments' => '', 'feedback_date' => null])
        ->call('create')
        ->assertHasFormErrors(['customer_id' => 'required', 'rating' => 'required', 'feedback_date' => 'required']);
});

it('performs customer feedback crud operations', function (): void {
    actingAs(makeCustomerFeedbackAdminUser());
    $customer = createCustomerFeedbackCustomer();
    $branch = createCustomerFeedbackBranch();

    Livewire::test(CreateCustomerFeedback::class)
        ->fillForm(['customer_id' => $customer->getKey(), 'branch_id' => $branch->getKey(), 'rating' => 5, 'comments' => 'Great service', 'feedback_date' => '2026-07-21'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CustomerFeedback::query()->firstOrFail();
    Livewire::test(ListCustomerFeedback::class)->assertSuccessful()->assertSee('Feedback Customer');

    Livewire::test(EditCustomerFeedback::class, ['record' => $record->getKey()])
        ->fillForm(['customer_id' => $customer->getKey(), 'branch_id' => $branch->getKey(), 'rating' => 4, 'comments' => 'Good service', 'feedback_date' => '2026-07-22'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((int) $record->rating)->toBe(4);

    Livewire::test(EditCustomerFeedback::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CustomerFeedback::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
