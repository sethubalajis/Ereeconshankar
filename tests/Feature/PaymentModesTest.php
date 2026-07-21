<?php

use App\Filament\Resources\PaymentModes\Pages\CreatePaymentMode;
use App\Filament\Resources\PaymentModes\Pages\EditPaymentMode;
use App\Filament\Resources\PaymentModes\Pages\ListPaymentModes;
use App\Models\PaymentMode;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePaymentModesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from payment modes list', function (): void { get('/admin/payment-modes')->assertRedirect('/admin/login'); });
it('redirects guests away from create payment mode page', function (): void { get('/admin/payment-modes/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a payment mode', function (): void {
    actingAs(makePaymentModesAdminUser());

    Livewire::test(CreatePaymentMode::class)
        ->fillForm(['name' => '', 'is_active' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'is_active' => 'required']);
});

it('performs payment mode crud operations', function (): void {
    actingAs(makePaymentModesAdminUser());

    Livewire::test(CreatePaymentMode::class)
        ->fillForm(['name' => 'UPI', 'is_active' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PaymentMode::query()->firstOrFail();
    Livewire::test(ListPaymentModes::class)->assertSuccessful()->assertSee('UPI');

    Livewire::test(EditPaymentMode::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Card', 'is_active' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Card');

    Livewire::test(EditPaymentMode::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PaymentMode::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
