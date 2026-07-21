<?php

use App\Filament\Resources\TransferApprovals\Pages\CreateTransferApproval;
use App\Filament\Resources\TransferApprovals\Pages\EditTransferApproval;
use App\Filament\Resources\TransferApprovals\Pages\ListTransferApprovals;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\TransferApproval;
use App\Models\TransferRequest;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTransferApprovalsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTransferApprovalRequest(): TransferRequest
{
    $company = Company::query()->create(['name' => 'TA Co', 'code' => 'TACO1', 'status' => true]);
    $from = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TA From', 'code' => 'TAF01', 'status' => true]);
    $to = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TA To', 'code' => 'TAT01', 'status' => true]);

    return TransferRequest::query()->create([
        'from_branch_id' => $from->getKey(),
        'to_branch_id' => $to->getKey(),
        'request_date' => '2026-07-21',
        'status' => 'pending',
    ]);
}

it('redirects guests away from transfer approvals list', function (): void {
    get('/admin/transfer-approvals')->assertRedirect('/admin/login');
});

it('redirects guests away from create transfer approval page', function (): void {
    get('/admin/transfer-approvals/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a transfer approval', function (): void {
    actingAs(makeTransferApprovalsAdminUser());

    Livewire::test(CreateTransferApproval::class)
        ->fillForm(['transfer_request_id' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['transfer_request_id' => 'required', 'status' => 'required']);
});

it('performs transfer approval crud operations', function (): void {
    actingAs(makeTransferApprovalsAdminUser());
    $request = createTransferApprovalRequest();
    $approver = User::factory()->create();

    Livewire::test(CreateTransferApproval::class)
        ->fillForm([
            'transfer_request_id' => $request->getKey(),
            'approver_id' => $approver->getKey(),
            'approval_date' => '2026-07-21',
            'status' => 'pending',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TransferApproval::query()->firstOrFail();
    Livewire::test(ListTransferApprovals::class)->assertSuccessful()->assertSee('pending');

    Livewire::test(EditTransferApproval::class, ['record' => $record->getKey()])
        ->fillForm([
            'transfer_request_id' => $request->getKey(),
            'approver_id' => $approver->getKey(),
            'approval_date' => '2026-07-22',
            'status' => 'approved',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('approved');

    Livewire::test(EditTransferApproval::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TransferApproval::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
