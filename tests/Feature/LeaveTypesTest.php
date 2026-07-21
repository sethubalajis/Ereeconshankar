<?php

use App\Filament\Resources\LeaveTypes\Pages\CreateLeaveType;
use App\Filament\Resources\LeaveTypes\Pages\EditLeaveType;
use App\Filament\Resources\LeaveTypes\Pages\ListLeaveTypes;
use App\Models\LeaveType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeLeaveTypesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from leave types list', function (): void { get('/admin/leave-types')->assertRedirect('/admin/login'); });
it('redirects guests away from create leave type page', function (): void { get('/admin/leave-types/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a leave type', function (): void {
    actingAs(makeLeaveTypesAdminUser());

    Livewire::test(CreateLeaveType::class)
        ->fillForm(['name' => '', 'days_allowed' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'days_allowed' => 'required', 'status' => 'required']);
});

it('performs leave type crud operations', function (): void {
    actingAs(makeLeaveTypesAdminUser());

    Livewire::test(CreateLeaveType::class)
        ->fillForm(['name' => 'Annual Leave', 'days_allowed' => 12, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = LeaveType::query()->firstOrFail();
    Livewire::test(ListLeaveTypes::class)->assertSuccessful()->assertSee('Annual Leave');

    Livewire::test(EditLeaveType::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Sick Leave', 'days_allowed' => 10, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Sick Leave');

    Livewire::test(EditLeaveType::class, ['record' => $record->getKey()])->callAction('delete');
    expect(LeaveType::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
