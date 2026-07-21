<?php

use App\Filament\Resources\PayrollCycles\Pages\CreatePayrollCycle;
use App\Filament\Resources\PayrollCycles\Pages\EditPayrollCycle;
use App\Filament\Resources\PayrollCycles\Pages\ListPayrollCycles;
use App\Models\PayrollCycle;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePayrollCyclesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from payroll cycles list', function (): void { get('/admin/payroll-cycles')->assertRedirect('/admin/login'); });
it('redirects guests away from create payroll cycle page', function (): void { get('/admin/payroll-cycles/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a payroll cycle', function (): void {
    actingAs(makePayrollCyclesAdminUser());

    Livewire::test(CreatePayrollCycle::class)
        ->fillForm(['name' => '', 'start_date' => null, 'end_date' => null, 'payment_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'start_date' => 'required', 'end_date' => 'required', 'status' => 'required']);
});

it('performs payroll cycle crud operations', function (): void {
    actingAs(makePayrollCyclesAdminUser());

    Livewire::test(CreatePayrollCycle::class)
        ->fillForm(['name' => 'July Cycle', 'start_date' => '2026-07-01', 'end_date' => '2026-07-31', 'payment_date' => '2026-08-05', 'status' => 'draft'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PayrollCycle::query()->firstOrFail();
    Livewire::test(ListPayrollCycles::class)->assertSuccessful()->assertSee('July Cycle');

    Livewire::test(EditPayrollCycle::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'August Cycle', 'start_date' => '2026-08-01', 'end_date' => '2026-08-31', 'payment_date' => '2026-09-05', 'status' => 'processed'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('August Cycle');

    Livewire::test(EditPayrollCycle::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PayrollCycle::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
