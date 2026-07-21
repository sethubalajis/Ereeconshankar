<?php

use App\Filament\Resources\CashBooks\Pages\CreateCashBook;
use App\Filament\Resources\CashBooks\Pages\EditCashBook;
use App\Filament\Resources\CashBooks\Pages\ListCashBooks;
use App\Models\CashBook;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeCashBooksAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from cash books list', function (): void { get('/admin/cash-books')->assertRedirect('/admin/login'); });
it('redirects guests away from create cash book page', function (): void { get('/admin/cash-books/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a cash book', function (): void {
    actingAs(makeCashBooksAdminUser());
    Livewire::test(CreateCashBook::class)
        ->fillForm(['date' => null, 'description' => '', 'receipt_amount' => null, 'payment_amount' => null, 'balance' => null])
        ->call('create')
        ->assertHasFormErrors(['date' => 'required', 'receipt_amount' => 'required', 'payment_amount' => 'required', 'balance' => 'required']);
});

it('performs cash book crud operations', function (): void {
    actingAs(makeCashBooksAdminUser());

    Livewire::test(CreateCashBook::class)
        ->fillForm(['date' => '2026-07-21', 'description' => 'Opening cash', 'receipt_amount' => 1000, 'payment_amount' => 200, 'balance' => 800])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = CashBook::query()->firstOrFail();
    Livewire::test(ListCashBooks::class)->assertSuccessful()->assertSee('Opening cash');

    Livewire::test(EditCashBook::class, ['record' => $record->getKey()])
        ->fillForm(['date' => '2026-07-22', 'description' => 'Updated cash', 'receipt_amount' => 1200, 'payment_amount' => 300, 'balance' => 900])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->description)->toBe('Updated cash');

    Livewire::test(EditCashBook::class, ['record' => $record->getKey()])->callAction('delete');
    expect(CashBook::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
