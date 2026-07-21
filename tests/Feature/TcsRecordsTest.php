<?php

use App\Filament\Resources\TcsRecords\Pages\CreateTcsRecord;
use App\Filament\Resources\TcsRecords\Pages\EditTcsRecord;
use App\Filament\Resources\TcsRecords\Pages\ListTcsRecords;
use App\Models\Role;
use App\Models\TcsRecord;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTcsRecordsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from tcs records list', function (): void {
    get('/admin/tcs-records')->assertRedirect('/admin/login');
});

it('redirects guests away from create tcs record page', function (): void {
    get('/admin/tcs-records/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a tcs record', function (): void {
    actingAs(makeTcsRecordsAdminUser());

    Livewire::test(CreateTcsRecord::class)
        ->fillForm([
            'section_code' => '',
            'tcs_percentage' => null,
            'collectee_id' => null,
            'collectee_type' => '',
            'amount' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'section_code' => 'required',
            'tcs_percentage' => 'required',
            'collectee_id' => 'required',
            'collectee_type' => 'required',
            'amount' => 'required',
        ]);
});

it('performs tcs record crud operations', function (): void {
    actingAs(makeTcsRecordsAdminUser());

    Livewire::test(CreateTcsRecord::class)
        ->fillForm([
            'section_code' => '206C',
            'tcs_percentage' => 1,
            'collectee_id' => 1,
            'collectee_type' => 'App\\Models\\Customer',
            'amount' => 25000,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TcsRecord::query()->firstOrFail();
    Livewire::test(ListTcsRecords::class)->assertSuccessful()->assertSee('206C');

    Livewire::test(EditTcsRecord::class, ['record' => $record->getKey()])
        ->fillForm([
            'section_code' => '206C1H',
            'tcs_percentage' => 0.1,
            'collectee_id' => 2,
            'collectee_type' => 'App\\Models\\Vendor',
            'amount' => 30000,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->amount)->toBe(30000.0);

    Livewire::test(EditTcsRecord::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TcsRecord::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
