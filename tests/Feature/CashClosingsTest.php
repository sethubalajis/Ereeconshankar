<?php

use App\Filament\Resources\CashClosings\Pages\CreateCashClosing;
use App\Filament\Resources\CashClosings\Pages\EditCashClosing;
use App\Filament\Resources\CashClosings\Pages\ListCashClosings;
use App\Models\Branch;
use App\Models\CashClosing;
use App\Models\CashRegister;
use App\Models\Company;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCashClosingsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCashRegisterForClosings(): CashRegister
{
    $company = Company::query()->create(['name' => 'Closing Co', 'code' => 'CLC001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Closing Branch', 'code' => 'CLB001', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'Terminal 1', 'terminal_code' => 'POS001', 'status' => true]);
    $user = User::factory()->create();

    return CashRegister::query()->create(['pos_terminal_id' => $terminal->getKey(), 'user_id' => $user->getKey(), 'date' => '2026-07-21', 'opening_balance' => 1000, 'closing_balance' => 1500, 'status' => 'open']);
}

it('redirects guests away from cash closings list', function (): void { get('/admin/cash-closings')->assertRedirect('/admin/login'); });
it('redirects guests away from create cash closing page', function (): void { get('/admin/cash-closings/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a cash closing', function (): void {
    actingAs(makeCashClosingsAdminUser());
    Livewire::test(CreateCashClosing::class)
        ->fillForm(['cash_register_id' => null, 'closing_time' => null, 'closing_amount' => null, 'variance' => null])
        ->call('create')
        ->assertHasFormErrors(['cash_register_id' => 'required', 'closing_time' => 'required', 'closing_amount' => 'required', 'variance' => 'required']);
});

it('performs cash closing crud operations', function (): void {
    actingAs(makeCashClosingsAdminUser());
    $cashRegister = createCashRegisterForClosings();

    Livewire::test(CreateCashClosing::class)
        ->fillForm(['cash_register_id' => $cashRegister->getKey(), 'closing_time' => '2026-07-21 18:00:00', 'closing_amount' => 1500, 'variance' => 0])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CashClosing::query()->firstOrFail();
    Livewire::test(ListCashClosings::class)->assertSuccessful()->assertSee('1,500');

    Livewire::test(EditCashClosing::class, ['record' => $record->getKey()])
        ->fillForm(['cash_register_id' => $cashRegister->getKey(), 'closing_time' => '2026-07-21 19:00:00', 'closing_amount' => 1600, 'variance' => 100])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->closing_amount)->toBe(1600.0);

    Livewire::test(EditCashClosing::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CashClosing::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
