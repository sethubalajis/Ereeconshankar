<?php

use App\Filament\Resources\GiftVouchers\Pages\CreateGiftVoucher;
use App\Filament\Resources\GiftVouchers\Pages\EditGiftVoucher;
use App\Filament\Resources\GiftVouchers\Pages\ListGiftVouchers;
use App\Models\GiftVoucher;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGiftVouchersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from gift vouchers list', function (): void { get('/admin/gift-vouchers')->assertRedirect('/admin/login'); });
it('redirects guests away from create gift voucher page', function (): void { get('/admin/gift-vouchers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a gift voucher', function (): void {
    actingAs(makeGiftVouchersAdminUser());
    Livewire::test(CreateGiftVoucher::class)
        ->fillForm(['voucher_code' => '', 'amount' => null, 'expiry_date' => null, 'is_redeemed' => null])
        ->call('create')
        ->assertHasFormErrors(['voucher_code' => 'required', 'amount' => 'required', 'is_redeemed' => 'required']);
});

it('performs gift voucher crud operations', function (): void {
    actingAs(makeGiftVouchersAdminUser());

    Livewire::test(CreateGiftVoucher::class)
        ->fillForm(['voucher_code' => 'GV001', 'amount' => 1000, 'expiry_date' => '2026-12-31', 'is_redeemed' => false])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = GiftVoucher::query()->firstOrFail();
    Livewire::test(ListGiftVouchers::class)->assertSuccessful()->assertSee('GV001');

    Livewire::test(EditGiftVoucher::class, ['record' => $record->getKey()])
        ->fillForm(['voucher_code' => 'GV002', 'amount' => 1500, 'expiry_date' => '2027-01-31', 'is_redeemed' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->voucher_code)->toBe('GV002');

    Livewire::test(EditGiftVoucher::class, ['record' => $record->getKey()])->callAction('delete');
    expect(GiftVoucher::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
