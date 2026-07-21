<?php

use App\Filament\Resources\Payments\Pages\CreatePayment;
use App\Filament\Resources\Payments\Pages\EditPayment;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePaymentsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from payments list', function (): void { get('/admin/payments')->assertRedirect('/admin/login'); });
it('redirects guests away from create payment page', function (): void { get('/admin/payments/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a payment', function (): void {
    actingAs(makePaymentsAdminUser());

    Livewire::test(CreatePayment::class)
        ->fillForm(['payment_date' => null, 'amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['payment_date' => 'required', 'amount' => 'required', 'status' => 'required']);
});

it('performs payment crud operations', function (): void {
    actingAs(makePaymentsAdminUser());

    Livewire::test(CreatePayment::class)
        ->fillForm(['payment_date' => '2026-07-21', 'amount' => 2500, 'reference_no' => 'PAY-001', 'status' => 'completed'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Payment::query()->firstOrFail();
    Livewire::test(ListPayments::class)->assertSuccessful()->assertSee('PAY-001');

    Livewire::test(EditPayment::class, ['record' => $record->getKey()])
        ->fillForm(['payment_date' => '2026-07-22', 'amount' => 3000, 'reference_no' => 'PAY-002', 'status' => 'pending'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->reference_no)->toBe('PAY-002');

    Livewire::test(EditPayment::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Payment::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
