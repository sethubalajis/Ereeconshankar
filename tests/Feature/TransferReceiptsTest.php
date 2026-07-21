<?php

use App\Filament\Resources\TransferReceipts\Pages\CreateTransferReceipt;
use App\Filament\Resources\TransferReceipts\Pages\EditTransferReceipt;
use App\Filament\Resources\TransferReceipts\Pages\ListTransferReceipts;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\StockTransfer;
use App\Models\TransferReceipt;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTransferReceiptsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createTransferReceiptTransfer(): StockTransfer
{
    $company = Company::query()->create(['name' => 'TRC Co', 'code' => 'TRCC1', 'status' => true]);
    $from = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TRC From', 'code' => 'TRCF1', 'status' => true]);
    $to = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'TRC To', 'code' => 'TRCT1', 'status' => true]);

    return StockTransfer::query()->create([
        'from_branch_id' => $from->getKey(),
        'to_branch_id' => $to->getKey(),
        'transfer_date' => '2026-07-21',
        'vehicle_number' => 'TN55ZZ0099',
        'status' => 'in_transit',
    ]);
}

it('redirects guests away from transfer receipts list', function (): void {
    get('/admin/transfer-receipts')->assertRedirect('/admin/login');
});

it('redirects guests away from create transfer receipt page', function (): void {
    get('/admin/transfer-receipts/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a transfer receipt', function (): void {
    actingAs(makeTransferReceiptsAdminUser());

    Livewire::test(CreateTransferReceipt::class)
        ->fillForm(['stock_transfer_id' => null, 'receive_date' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['stock_transfer_id' => 'required', 'receive_date' => 'required', 'status' => 'required']);
});

it('performs transfer receipt crud operations', function (): void {
    actingAs(makeTransferReceiptsAdminUser());
    $transfer = createTransferReceiptTransfer();
    $receiver = User::factory()->create();

    Livewire::test(CreateTransferReceipt::class)
        ->fillForm([
            'stock_transfer_id' => $transfer->getKey(),
            'receive_date' => '2026-07-22',
            'received_by' => $receiver->getKey(),
            'status' => 'received',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TransferReceipt::query()->firstOrFail();
    Livewire::test(ListTransferReceipts::class)->assertSuccessful()->assertSee('TN55ZZ0099');

    Livewire::test(EditTransferReceipt::class, ['record' => $record->getKey()])
        ->fillForm([
            'stock_transfer_id' => $transfer->getKey(),
            'receive_date' => '2026-07-23',
            'received_by' => $receiver->getKey(),
            'status' => 'partial',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('partial');

    Livewire::test(EditTransferReceipt::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TransferReceipt::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
