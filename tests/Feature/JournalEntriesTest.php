<?php

use App\Filament\Resources\JournalEntries\Pages\CreateJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\EditJournalEntry;
use App\Filament\Resources\JournalEntries\Pages\ListJournalEntries;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeJournalEntriesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createJournalEntriesSetup(): array
{
    $group = AccountGroup::query()->create(['name' => 'Journal Group', 'type' => 'asset']);
    $account = ChartOfAccount::query()->create([
        'account_group_id' => $group->getKey(),
        'account_name' => 'Journal Cash',
        'account_type' => 'asset',
        'opening_balance' => 0,
        'current_balance' => 0,
    ]);

    $journal = Journal::query()->create([
        'journal_date' => '2026-07-21',
        'reference' => 'JE-REF-1',
        'total_debit' => 5000,
        'total_credit' => 5000,
        'status' => 'posted',
    ]);

    return compact('journal', 'account');
}

it('redirects guests away from journal entries list', function (): void { get('/admin/journal-entries')->assertRedirect('/admin/login'); });
it('redirects guests away from create journal entry page', function (): void { get('/admin/journal-entries/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a journal entry', function (): void {
    actingAs(makeJournalEntriesAdminUser());

    Livewire::test(CreateJournalEntry::class)
        ->fillForm(['journal_id' => null, 'chart_of_account_id' => null, 'description' => '', 'debit' => null, 'credit' => null])
        ->call('create')
        ->assertHasFormErrors(['journal_id' => 'required', 'chart_of_account_id' => 'required', 'debit' => 'required', 'credit' => 'required']);
});

it('performs journal entry crud operations', function (): void {
    actingAs(makeJournalEntriesAdminUser());
    ['journal' => $journal, 'account' => $account] = createJournalEntriesSetup();

    Livewire::test(CreateJournalEntry::class)
        ->fillForm(['journal_id' => $journal->getKey(), 'chart_of_account_id' => $account->getKey(), 'description' => 'Opening adjustment', 'debit' => 5000, 'credit' => 0])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = JournalEntry::query()->firstOrFail();
    Livewire::test(ListJournalEntries::class)->assertSuccessful()->assertSee('Opening adjustment');

    Livewire::test(EditJournalEntry::class, ['record' => $record->getKey()])
        ->fillForm(['journal_id' => $journal->getKey(), 'chart_of_account_id' => $account->getKey(), 'description' => 'Closing adjustment', 'debit' => 0, 'credit' => 5000])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->description)->toBe('Closing adjustment');

    Livewire::test(EditJournalEntry::class, ['record' => $record->getKey()])->callAction('delete');
    expect(JournalEntry::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
