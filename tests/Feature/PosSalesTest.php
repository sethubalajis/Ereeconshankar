<?php

use App\Filament\Resources\PosSales\Pages\CreatePosSale;
use App\Filament\Resources\PosSales\Pages\EditPosSale;
use App\Filament\Resources\PosSales\Pages\ListPosSales;
use App\Models\Branch;
use App\Models\Company;
use App\Models\PosSale;
use App\Models\PosTerminal;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makePosSalesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

function createPosSaleBranchAndTerminal(): array
{
    $company = Company::query()->create(['name' => 'POS Sale Co', 'code' => 'PSC01', 'status' => true]);
    $branch = Branch::query()->create(['company_id' => $company->getKey(), 'name' => 'POS Sale Branch', 'code' => 'PSB02', 'status' => true]);
    $terminal = PosTerminal::query()->create(['branch_id' => $branch->getKey(), 'terminal_name' => 'POS Main', 'terminal_code' => 'POSM1', 'status' => true]);
    return compact('branch', 'terminal');
}

it('redirects guests away from pos sales list', function (): void { get('/admin/pos-sales')->assertRedirect('/admin/login'); });
it('redirects guests away from create pos sale page', function (): void { get('/admin/pos-sales/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a pos sale', function (): void {
    actingAs(makePosSalesAdminUser());

    Livewire::test(CreatePosSale::class)
        ->fillForm(['branch_id' => null, 'pos_terminal_id' => null, 'sale_date' => null, 'total_amount' => null, 'tax_amount' => null, 'discount_amount' => null, 'net_amount' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['branch_id' => 'required', 'pos_terminal_id' => 'required', 'sale_date' => 'required', 'total_amount' => 'required', 'tax_amount' => 'required', 'discount_amount' => 'required', 'net_amount' => 'required', 'status' => 'required']);
});

it('performs pos sale crud operations', function (): void {
    actingAs(makePosSalesAdminUser());
    ['branch' => $branch, 'terminal' => $terminal] = createPosSaleBranchAndTerminal();

    Livewire::test(CreatePosSale::class)
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'pos_terminal_id' => $terminal->getKey(),
            'sale_date' => '2026-07-21',
            'total_amount' => 1200,
            'tax_amount' => 120,
            'discount_amount' => 20,
            'net_amount' => 1300,
            'status' => 'completed',
            'posSaleItems' => [],
            'payments' => [],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = PosSale::query()->firstOrFail();
    Livewire::test(ListPosSales::class)->assertSuccessful()->assertSee($record->invoice_number);

    Livewire::test(EditPosSale::class, ['record' => $record->getKey()])
        ->fillForm([
            'branch_id' => $branch->getKey(),
            'pos_terminal_id' => $terminal->getKey(),
            'sale_date' => '2026-07-22',
            'total_amount' => 1500,
            'tax_amount' => 150,
            'discount_amount' => 50,
            'net_amount' => 1600,
            'status' => 'pending',
            'posSaleItems' => [],
            'payments' => [],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->status)->toBe('pending');

    Livewire::test(EditPosSale::class, ['record' => $record->getKey()])->callAction('delete');
    expect(PosSale::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
