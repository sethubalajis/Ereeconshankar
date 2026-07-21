<?php

use App\Filament\Resources\CashRegisters\Pages\CreateCashRegister;
use App\Filament\Resources\CashRegisters\Pages\EditCashRegister;
use App\Filament\Resources\CashRegisters\Pages\ListCashRegisters;
use App\Models\Branch;
use App\Models\CashRegister;
use App\Models\Company;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCashRegistersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCashRegisterSetup(): array
{
    $company = Company::query()->create(['name' => 'Register Co', 'code' => 'RGC001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Register Branch', 'code' => 'RGB001', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'POS Alpha', 'terminal_code' => 'POS100', 'status' => true]);
    $user = User::factory()->create();

    return compact('terminal', 'user');
}

it('redirects guests away from cash registers list', function (): void { get('/admin/cash-registers')->assertRedirect('/admin/login'); });
it('redirects guests away from create cash register page', function (): void { get('/admin/cash-registers/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a cash register', function (): void {
    actingAs(makeCashRegistersAdminUser());
    Livewire::test(CreateCashRegister::class)
        ->fillForm(['pos_terminal_id' => null, 'user_id' => null, 'date' => null, 'opening_balance' => null, 'closing_balance' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['pos_terminal_id' => 'required', 'user_id' => 'required', 'date' => 'required', 'opening_balance' => 'required', 'status' => 'required']);
});

it('performs cash register crud operations', function (): void {
    actingAs(makeCashRegistersAdminUser());
    ['terminal' => $terminal, 'user' => $user] = createCashRegisterSetup();

    Livewire::test(CreateCashRegister::class)
        ->fillForm(['pos_terminal_id' => $terminal->getKey(), 'user_id' => $user->getKey(), 'date' => '2026-07-21', 'opening_balance' => 1000, 'closing_balance' => 1200, 'status' => 'open'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CashRegister::query()->firstOrFail();
    Livewire::test(ListCashRegisters::class)->assertSuccessful()->assertSee('POS Alpha')->assertSee($user->name);

    Livewire::test(EditCashRegister::class, ['record' => $record->getKey()])
        ->fillForm(['pos_terminal_id' => $terminal->getKey(), 'user_id' => $user->getKey(), 'date' => '2026-07-22', 'opening_balance' => 1500, 'closing_balance' => 1700, 'status' => 'closed'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('closed');

    Livewire::test(EditCashRegister::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CashRegister::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
