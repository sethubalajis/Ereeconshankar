<?php

use App\Filament\Resources\ProductLines\Pages\CreateProductLine;
use App\Filament\Resources\ProductLines\Pages\EditProductLine;
use App\Filament\Resources\ProductLines\Pages\ListProductLines;
use App\Models\ProductLine;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeProductLinesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from product lines list', function (): void { get('/admin/product-lines')->assertRedirect('/admin/login'); });
it('redirects guests away from create product line page', function (): void { get('/admin/product-lines/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a product line', function (): void {
    actingAs(makeProductLinesAdminUser());

    Livewire::test(CreateProductLine::class)
        ->fillForm(['name' => '', 'code' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs product line crud operations', function (): void {
    actingAs(makeProductLinesAdminUser());

    Livewire::test(CreateProductLine::class)
        ->fillForm(['name' => 'Premium Line', 'code' => 'PLN01', 'description' => 'Premium products', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = ProductLine::query()->firstOrFail();
    Livewire::test(ListProductLines::class)->assertSuccessful()->assertSee('Premium Line');

    Livewire::test(EditProductLine::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Budget Line', 'code' => 'PLN02', 'description' => 'Budget products', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->code)->toBe('PLN02');

    Livewire::test(EditProductLine::class, ['record' => $record->getKey()])->callAction('delete');
    expect(ProductLine::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
