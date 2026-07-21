<?php

use App\Filament\Resources\Shifts\Pages\CreateShift;
use App\Filament\Resources\Shifts\Pages\EditShift;
use App\Filament\Resources\Shifts\Pages\ListShifts;
use App\Models\Role;
use App\Models\Shift;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeShiftsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from shifts list', function (): void {
    get('/admin/shifts')->assertRedirect('/admin/login');
});

it('redirects guests away from create shift page', function (): void {
    get('/admin/shifts/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a shift', function (): void {
    actingAs(makeShiftsAdminUser());

    Livewire::test(CreateShift::class)
        ->fillForm([
            'name' => '',
            'start_time' => null,
            'end_time' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ]);
});

it('performs shift crud operations', function (): void {
    actingAs(makeShiftsAdminUser());

    Livewire::test(CreateShift::class)
        ->fillForm([
            'name' => 'Morning Shift',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'break_time' => '01:00',
            'status' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Shift::query()->firstOrFail();

    Livewire::test(ListShifts::class)
        ->assertSuccessful()
        ->assertSee('Morning Shift');

    Livewire::test(EditShift::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'Evening Shift',
            'start_time' => '10:00',
            'end_time' => '18:00',
            'break_time' => '00:30',
            'status' => false,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Evening Shift');

    Livewire::test(EditShift::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Shift::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
