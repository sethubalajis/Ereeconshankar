<?php

use App\Filament\Resources\PosTerminals\Pages\CreatePosTerminal;
use App\Filament\Resources\PosTerminals\Pages\EditPosTerminal;
use App\Filament\Resources\PosTerminals\Pages\ListPosTerminals;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosTerminalsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosTerminalBranch(): Branch
{
    $company = Company::query()->create(['name' => 'Terminal Co', 'code' => 'TRM01', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Terminal Branch', 'code' => 'TRB01', 'status' => true]);
}

it('redirects guests away from pos terminals list', function (): void { get('/admin/pos-terminals')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos terminal page', function (): void { get('/admin/pos-terminals/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos terminal', function (): void {
    actingAs(makePosTerminalsAdminUser());

    Livewire::test(CreatePosTerminal::class)
        ->fillForm(['branch_id' => null, 'terminal_name' => '', 'terminal_code' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'terminal_name' => 'required', 'terminal_code' => 'required', 'status' => 'required']);
});

it('performs pos terminal crud operations', function (): void {
    actingAs(makePosTerminalsAdminUser());
    $branch = createPosTerminalBranch();

    Livewire::test(CreatePosTerminal::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'terminal_name' => 'Counter-A', 'terminal_code' => 'T-A1', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosTerminal::query()->firstOrFail();
    Livewire::test(ListPosTerminals::class)->assertSuccessful()->assertSee('Counter-A');

    Livewire::test(EditPosTerminal::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'terminal_name' => 'Counter-B', 'terminal_code' => 'T-B1', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->terminal_name)->toBe('Counter-B');

    Livewire::test(EditPosTerminal::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosTerminal::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
