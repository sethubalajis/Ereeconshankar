<?php

use App\Filament\Resources\PosReturns\Pages\CreatePosReturn;
use App\Filament\Resources\PosReturns\Pages\EditPosReturn;
use App\Filament\Resources\PosReturns\Pages\ListPosReturns;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosReturn;
use App\Models\PosSale;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosReturnsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosReturnSale(): PosSale
{
    $company = Company::query()->create(['name' => 'Return Co', 'code' => 'RET01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'Return Branch', 'code' => 'REB01', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'Return Terminal', 'terminal_code' => 'RET1', 'status' => true]);

    return PosSale::query()->create([
        'branch_id' => $branch->getKey(),
        'pos_terminal_id' => $terminal->getKey(),
        'invoice_number' => 'POS-RET-001',
        'sale_date' => '2026-07-21',
        'total_amount' => 2000,
        'tax_amount' => 200,
        'discount_amount' => 0,
        'net_amount' => 2200,
        'status' => 'completed',
    ]);
}

it('redirects guests away from pos returns list', function (): void { get('/admin/pos-returns')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos return page', function (): void { get('/admin/pos-returns/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos return', function (): void {
    actingAs(makePosReturnsAdminUser());

    Livewire::test(CreatePosReturn::class)
        ->fillForm(['pos_sale_id' => null, 'return_date' => null, 'return_amount' => null, 'reason' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['pos_sale_id' => 'required', 'return_date' => 'required', 'return_amount' => 'required', 'status' => 'required']);
});

it('performs pos return crud operations', function (): void {
    actingAs(makePosReturnsAdminUser());
    $sale = createPosReturnSale();

    Livewire::test(CreatePosReturn::class)
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'return_date' => '2026-07-22', 'return_amount' => 500, 'reason' => 'Damaged item', 'status' => 'completed'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosReturn::query()->firstOrFail();
    Livewire::test(ListPosReturns::class)->assertSuccessful()->assertSee('POS-RET-001');

    Livewire::test(EditPosReturn::class, ['record' => $record->getKey()])
        ->fillForm(['pos_sale_id' => $sale->getKey(), 'return_date' => '2026-07-23', 'return_amount' => 300, 'reason' => 'Partial return', 'status' => 'pending'])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->return_amount)->toBe(300.0);

    Livewire::test(EditPosReturn::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosReturn::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
