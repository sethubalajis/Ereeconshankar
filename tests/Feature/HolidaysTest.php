<?php

use App\Filament\Resources\Holidays\Pages\CreateHoliday;
use App\Filament\Resources\Holidays\Pages\EditHoliday;
use App\Filament\Resources\Holidays\Pages\ListHolidays;
use App\Models\Holiday;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeHolidaysAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from holidays list', function (): void { get('/admin/holidays')->assertRedirect('/admin/login'); });
it('redirects guests away from create holiday page', function (): void { get('/admin/holidays/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a holiday', function (): void {
    actingAs(makeHolidaysAdminUser());
    Livewire::test(CreateHoliday::class)
        ->fillForm(['name' => '', 'date' => null, 'description' => '', 'is_restricted' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'date' => 'required', 'is_restricted' => 'required']);
});

it('performs holiday crud operations', function (): void {
    actingAs(makeHolidaysAdminUser());

    Livewire::test(CreateHoliday::class)
        ->fillForm(['name' => 'Independence Day', 'date' => '2026-08-15', 'description' => 'National holiday', 'is_restricted' => false])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Holiday::query()->firstOrFail();
    Livewire::test(ListHolidays::class)->assertSuccessful()->assertSee('Independence Day');

    Livewire::test(EditHoliday::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Republic Day', 'date' => '2027-01-26', 'description' => 'National day', 'is_restricted' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditHoliday::class, ['record' => $record->getKey()])->callAction('delete');
});
