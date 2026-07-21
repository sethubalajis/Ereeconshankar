<?php

use App\Filament\Resources\TransferRequests\Pages\CreateTransferRequest;
use App\Filament\Resources\TransferRequests\Pages\EditTransferRequest;
use App\Filament\Resources\TransferRequests\Pages\ListTransferRequests;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\TransferRequest;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTransferRequestsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTransferRequestBranches(): array
{
    $company = Company::query()->create(['name' => 'TRQ Co', 'code' => 'TRQC1', 'status' => true]);
    $from = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TRQ From', 'code' => 'TRQF1', 'status' => true]);
    $to = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TRQ To', 'code' => 'TRQT1', 'status' => true]);

    return compact('from', 'to');
}

it('redirects guests away from transfer requests list', function (): void {
    get('/admin/transfer-requests')->assertRedirect('/admin/login');
});

it('redirects guests away from create transfer request page', function (): void {
    get('/admin/transfer-requests/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a transfer request', function (): void {
    actingAs(makeTransferRequestsAdminUser());

    Livewire::test(CreateTransferRequest::class)
        ->fillForm(['from_branch_id' => null, 'to_branch_id' => null, 'request_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['from_branch_id' => 'required', 'to_branch_id' => 'required', 'request_date' => 'required', 'status' => 'required']);
});

it('performs transfer request crud operations', function (): void {
    actingAs(makeTransferRequestsAdminUser());
    ['from' => $from, 'to' => $to] = createTransferRequestBranches();

    Livewire::test(CreateTransferRequest::class)
        ->fillForm([
            'from_branch_id' => $from->getKey(),
            'to_branch_id' => $to->getKey(),
            'request_date' => '2026-07-21',
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TransferRequest::query()->firstOrFail();
    Livewire::test(ListTransferRequests::class)->assertSuccessful()->assertSee('pending');

    Livewire::test(EditTransferRequest::class, ['record' => $record->getKey()])
        ->fillForm([
            'from_branch_id' => $from->getKey(),
            'to_branch_id' => $to->getKey(),
            'request_date' => '2026-07-22',
            'status' => 'approved',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditTransferRequest::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TransferRequest::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
