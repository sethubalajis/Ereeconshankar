<?php

use App\Filament\Resources\Journals\Pages\CreateJournal;
use App\Filament\Resources\Journals\Pages\EditJournal;
use App\Filament\Resources\Journals\Pages\ListJournals;
use App\Models\Journal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeJournalsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from journals list', function (): void { get('/admin/journals')->assertRedirect('/admin/login'); });
it('redirects guests away from create journal page', function (): void { get('/admin/journals/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a journal', function (): void {
    actingAs(makeJournalsAdminUser());

    Livewire::test(CreateJournal::class)
        ->fillForm(['journal_date' => null, 'reference' => '', 'total_debit' => null, 'total_credit' => null, 'notes' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['journal_date' => 'required', 'total_debit' => 'required', 'total_credit' => 'required', 'status' => 'required']);
});

it('performs journal crud operations', function (): void {
    actingAs(makeJournalsAdminUser());

    Livewire::test(CreateJournal::class)
        ->fillForm(['journal_date' => '2026-07-21', 'reference' => 'JOUR-001', 'total_debit' => 2500, 'total_credit' => 2500, 'notes' => 'Initial entry', 'status' => 'posted'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Journal::query()->firstOrFail();
    Livewire::test(ListJournals::class)->assertSuccessful()->assertSee('JOUR-001');

    Livewire::test(EditJournal::class, ['record' => $record->getKey()])
        ->fillForm(['journal_date' => '2026-07-22', 'reference' => 'JOUR-002', 'total_debit' => 3000, 'total_credit' => 3000, 'notes' => 'Updated entry', 'status' => 'draft'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->reference)->toBe('JOUR-002');

    Livewire::test(EditJournal::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Journal::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
