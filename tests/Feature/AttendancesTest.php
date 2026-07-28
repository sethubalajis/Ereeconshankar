<?php

use App\Filament\Resources\Attendances\Pages\CreateAttendance;
use App\Filament\Resources\Attendances\Pages\EditAttendance;
use App\Filament\Resources\Attendances\Pages\ListAttendances;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use App\Services\BiometricAttendanceImporter;
use Illuminate\Support\Facades\Storage;
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
        'biometric_code' => '2364',
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

it('imports biometric file and updates attendance check in and check out', function (): void {
    Storage::fake('local');

    Employee::query()->create([
        'first_name' => 'Biometric Emp',
        'biometric_code' => '2364',
        'status' => 'active',
    ]);

    $path = 'private/biometric/test.dat';

    Storage::disk('local')->put(
        $path,
        implode(PHP_EOL, [
            '05/02/2026,2364,21:30,I,001',
            '05/02/2026,2364,05:30,O,001',
            '05/02/2026,9999,09:00,I,001',
        ])
    );

    $result = app(BiometricAttendanceImporter::class)->importFromStoredPath($path);

    $attendance = Attendance::query()->firstOrFail();

    expect($result['processed'])->toBe(3);
    expect($result['created'])->toBe(1);
    expect($result['updated'])->toBe(1);
    expect($result['unmatched'])->toBe(1);
    expect($attendance->date)->toBe('2026-05-02');
    expect($attendance->check_in)->toBe('21:30:00');
    expect($attendance->check_out)->toBe('05:30:00');
    expect($attendance->status)->toBe('present');
});
