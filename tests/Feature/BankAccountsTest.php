<?php

use App\Filament\Resources\BankAccounts\Pages\CreateBankAccount;
use App\Filament\Resources\BankAccounts\Pages\EditBankAccount;
use App\Filament\Resources\BankAccounts\Pages\ListBankAccounts;
use App\Models\BankAccount;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeBankAccountsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from bank accounts list', function (): void { get('/admin/bank-accounts')->assertRedirect('/admin/login'); });
it('redirects guests away from create bank account page', function (): void { get('/admin/bank-accounts/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a bank account', function (): void {
    actingAs(makeBankAccountsAdminUser());
    Livewire::test(CreateBankAccount::class)
        ->fillForm(['bank_name' => '', 'account_number' => '', 'opening_balance' => null, 'current_balance' => null, 'is_default' => false])
        ->call('create')
        ->assertHasFormErrors(['bank_name' => 'required', 'account_number' => 'required', 'opening_balance' => 'required', 'current_balance' => 'required']);
});

it('performs bank account crud operations', function (): void {
    actingAs(makeBankAccountsAdminUser());

    Livewire::test(CreateBankAccount::class)
        ->fillForm(['bank_name' => 'HDFC', 'account_number' => '1234567890', 'ifsc_code' => 'HDFC0001', 'branch_name' => 'Main', 'account_type' => 'Current', 'opening_balance' => 1000, 'current_balance' => 1000, 'is_default' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = BankAccount::query()->where('account_number', '1234567890')->firstOrFail();
    Livewire::test(ListBankAccounts::class)->assertSuccessful()->assertSee('HDFC');

    Livewire::test(EditBankAccount::class, ['record' => $record->getKey()])
        ->fillForm(['bank_name' => 'ICICI', 'account_number' => '1234567891', 'ifsc_code' => 'ICIC0001', 'branch_name' => 'Main', 'account_type' => 'Savings', 'opening_balance' => 2000, 'current_balance' => 2500, 'is_default' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->bank_name)->toBe('ICICI');

    Livewire::test(EditBankAccount::class, ['record' => $record->getKey()])->callAction('delete');
    expect(BankAccount::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
