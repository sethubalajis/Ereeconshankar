<?php

use App\Filament\Resources\CashOpenings\Pages\CreateCashOpening;
use App\Filament\Resources\CashOpenings\Pages\EditCashOpening;
use App\Filament\Resources\CashOpenings\Pages\ListCashOpenings;
use App\Models\Branch;
use App\Models\CashClosing;
use App\Models\CashOpening;
use App\Models\CashRegister;
use App\Models\Company;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCashOpeningsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createCashRegisterForOpenings(): CashRegister
{
    $company = Company::query()->create(['name' => 'Opening Co', 'code' => 'OPC001', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Opening Branch', 'code' => 'OPB001', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'Terminal 2', 'terminal_code' => 'POS002', 'status' => true]);
    $user = User::factory()->create();

    $cashRegister = CashRegister::query()->create(['pos_terminal_id' => $terminal->getKey(), 'user_id' => $user->getKey(), 'date' => '2026-07-22', 'opening_balance' => 1200, 'closing_balance' => 1700, 'status' => 'open']);
    $previousRegister = CashRegister::query()->create(['pos_terminal_id' => $terminal->getKey(), 'user_id' => $user->getKey(), 'date' => '2026-07-21', 'opening_balance' => 1000, 'closing_balance' => 1400, 'status' => 'closed']);
    CashClosing::query()->create(['cash_register_id' => $previousRegister->getKey(), 'closing_time' => '2026-07-21 18:30:00', 'closing_amount' => 1400, 'variance' => 0]);

    return $cashRegister;
}

it('redirects guests away from cash openings list', function (): void { get('/admin/cash-openings')->assertRedirect('/admin/login'); });
it('redirects guests away from create cash opening page', function (): void { get('/admin/cash-openings/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a cash opening', function (): void {
    actingAs(makeCashOpeningsAdminUser());
    Livewire::test(CreateCashOpening::class)
        ->fillForm(['cash_register_id' => null, 'opening_time' => null, 'opening_amount' => null])
        ->call('create')
        ->assertHasFormErrors(['cash_register_id' => 'required', 'opening_time' => 'required', 'opening_amount' => 'required']);
});

it('performs cash opening crud operations', function (): void {
    actingAs(makeCashOpeningsAdminUser());
    $cashRegister = createCashRegisterForOpenings();

    Livewire::test(CreateCashOpening::class)
        ->fillForm(['cash_register_id' => $cashRegister->getKey(), 'opening_time' => '2026-07-22 09:00:00', 'opening_amount' => 1400])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CashOpening::query()->firstOrFail();
    Livewire::test(ListCashOpenings::class)->assertSuccessful()->assertSee('1,400');

    Livewire::test(EditCashOpening::class, ['record' => $record->getKey()])
        ->fillForm(['cash_register_id' => $cashRegister->getKey(), 'opening_time' => '2026-07-22 10:00:00', 'opening_amount' => 1450])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->opening_amount)->toBe(1450.0);

    Livewire::test(EditCashOpening::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CashOpening::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
