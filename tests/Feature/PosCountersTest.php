<?php

use App\Filament\Resources\PosCounters\Pages\CreatePosCounter;
use App\Filament\Resources\PosCounters\Pages\EditPosCounter;
use App\Filament\Resources\PosCounters\Pages\ListPosCounters;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosCounter;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosCountersAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosCounterBranch(): Branch
{
    $company = Company::query()->create(['name' => 'POS Co', 'code' => 'POS01', 'status' => true]);
    return Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'POS Branch', 'code' => 'PSB01', 'status' => true]);
}

it('redirects guests away from pos counters list', function (): void { get('/admin/pos-counters')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos counter page', function (): void { get('/admin/pos-counters/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos counter', function (): void {
    actingAs(makePosCountersAdminUser());

    Livewire::test(CreatePosCounter::class)
        ->fillForm(['branch_id' => null, 'counter_name' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'counter_name' => 'required', 'status' => 'required']);
});

it('performs pos counter crud operations', function (): void {
    actingAs(makePosCountersAdminUser());
    $branch = createPosCounterBranch();

    Livewire::test(CreatePosCounter::class)
        ->fillForm(['branch_id' => $branch->getKey(), 'counter_name' => 'Counter 1', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosCounter::query()->firstOrFail();
    Livewire::test(ListPosCounters::class)->assertSuccessful()->assertSee('Counter 1');

    Livewire::test(EditPosCounter::class, ['record' => $record->getKey()])
        ->fillForm(['branch_id' => $branch->getKey(), 'counter_name' => 'Counter 2', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->counter_name)->toBe('Counter 2');

    Livewire::test(EditPosCounter::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosCounter::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
