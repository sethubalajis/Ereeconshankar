<?php

use App\Filament\Resources\Receipts\Pages\CreateReceipt;
use App\Filament\Resources\Receipts\Pages\EditReceipt;
use App\Filament\Resources\Receipts\Pages\ListReceipts;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosSale;
use App\Models\PosTerminal;
use App\Models\Receipt;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeReceiptsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

function createReceiptSale(): PosSale
{
    $company = Company::query()->create(['name' => 'Receipt Co', 'code' => 'REC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Receipt Branch', 'code' => 'REB11', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'Receipt Terminal', 'terminal_code' => 'RCT01', 'status' => true]);

    return PosSale::query()->create([
        'branch_id' => $branch->getKey(),
        'pos_terminal_id' => $terminal->getKey(),
        'invoice_number' => 'REC-INV-001',
        'sale_date' => '2026-07-21',
        'total_amount' => 1000,
        'tax_amount' => 100,
        'discount_amount' => 0,
        'net_amount' => 1100,
        'status' => 'completed',
    ]);
}

it('redirects guests away from receipts list', function (): void {
    get('/admin/receipts')->assertRedirect('/admin/login');
});

it('redirects guests away from create receipt page', function (): void {
    get('/admin/receipts/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a receipt', function (): void {
    actingAs(makeReceiptsAdminUser());

    Livewire::test(CreateReceipt::class)
        ->fillForm(['pos_sale_id' => null, 'receipt_number' => ''])
        ->call('create')
        ->assertHasFormErrors(['pos_sale_id' => 'required', 'receipt_number' => 'required']);
});

it('performs receipt crud operations', function (): void {
    actingAs(makeReceiptsAdminUser());
    $sale = createReceiptSale();

    Livewire::test(CreateReceipt::class)
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'receipt_number' => 'REC-001', 'printed_at' => '2026-07-21 10:30:00'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Receipt::query()->firstOrFail();

    Livewire::test(ListReceipts::class)
        ->assertSuccessful()
        ->assertSee('REC-001');

    Livewire::test(EditReceipt::class, ['record' => $record->getKey()])
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'receipt_number' => 'REC-002', 'printed_at' => '2026-07-21 11:00:00'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->receipt_number)->toBe('REC-002');

    Livewire::test(EditReceipt::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Receipt::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
