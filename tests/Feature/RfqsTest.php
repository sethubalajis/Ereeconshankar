<?php

use App\Filament\Resources\Rfqs\Pages\CreateRfq;
use App\Filament\Resources\Rfqs\Pages\EditRfq;
use App\Filament\Resources\Rfqs\Pages\ListRfqs;
use App\Models\Rfq;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeRfqsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('redirects guests away from rfqs list', function (): void {
    get('/admin/rfqs')->assertRedirect('/admin/login');
});

it('redirects guests away from create rfq page', function (): void {
    get('/admin/rfqs/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a rfq', function (): void {
    actingAs(makeRfqsAdminUser());

    Livewire::test(CreateRfq::class)
        ->fillForm([
            'rfq_date' => null,
            'status' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'rfq_date' => 'required',
            'status' => 'required',
        ]);
});

it('performs rfq crud operations', function (): void {
    actingAs(makeRfqsAdminUser());

    Livewire::test(CreateRfq::class)
        ->fillForm([
            'rfq_date' => '2026-07-21',
            'submission_deadline' => '2026-07-30',
            'status' => 'open',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Rfq::query()->firstOrFail();

    Livewire::test(ListRfqs::class)
        ->assertSuccessful()
        ->assertSee($record->rfq_number);

    Livewire::test(EditRfq::class, ['record' => $record->getKey()])
        ->fillForm([
            'rfq_date' => '2026-07-22',
            'submission_deadline' => '2026-08-01',
            'status' => 'closed',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('closed');

    Livewire::test(EditRfq::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Rfq::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
