<?php

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeAttendancesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from attendances list', function (): void {
    get('/admin/attendances')->assertRedirect('/admin/login');
});

it('redirects guests away from create attendance page', function (): void {
    get('/admin/attendances/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating attendance', function (): void {
    actingAs(makeAttendancesAdminUser());

    Livewire::test(CreateAttendance::class)
        ->fillForm(['employee_id' => null, 'date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['employee_id' => 'required', 'date' => 'required', 'status' => 'required']);
});

it('performs attendance crud operations', function (): void {
    actingAs(makeAttendancesAdminUser());
    $employee = Employee::query()->create([
        'first_name' => 'Attendance Emp',
        'status' => 'active',
    ]);

    Livewire::test(CreateAttendance::class)
        ->fillForm([
            'employee_id' => $employee->getKey(),
            'date' => '2026-07-21',
            'check_in' => '09:00:00',
            'check_out' => '18:00:00',
            'status' => 'present',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Attendance::query()->firstOrFail();
    Livewire::test(ListAttendances::class)->assertSuccessful()->assertSee((string) $employee->first_name);

    Livewire::test(EditAttendance::class, ['record' => $record->getKey()])
        ->fillForm([
            'employee_id' => $employee->getKey(),
            'date' => '2026-07-22',
            'check_in' => '10:00:00',
            'check_out' => '17:00:00',
            'status' => 'half_day',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('half_day');

    Livewire::test(EditAttendance::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Attendance::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
