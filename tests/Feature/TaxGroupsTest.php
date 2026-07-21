<?php

use App\Filament\Resources\TaxGroups\Pages\CreateTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\EditTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\ListTaxGroups;
use App\Models\Role;
use App\Models\TaxGroup;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTaxGroupsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from tax groups list', function (): void {
    get('/admin/tax-groups')->assertRedirect('/admin/login');
});

it('redirects guests away from create tax group page', function (): void {
    get('/admin/tax-groups/create')->assertRedirect('/admin/login');
});

it('validates required fields when creating a tax group', function (): void {
    actingAs(makeTaxGroupsAdminUser());

    Livewire::test(CreateTaxGroup::class)
        ->fillForm(['name' => '', 'total_tax_percentage' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'total_tax_percentage' => 'required']);
});

it('performs tax group crud operations', function (): void {
    actingAs(makeTaxGroupsAdminUser());

    Livewire::test(CreateTaxGroup::class)
        ->fillForm(['name' => 'GST 18', 'description' => 'Standard GST', 'total_tax_percentage' => 18])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TaxGroup::query()->firstOrFail();
    Livewire::test(ListTaxGroups::class)->assertSuccessful()->assertSee('GST 18');

    Livewire::test(EditTaxGroup::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'GST 12', 'description' => 'Reduced GST', 'total_tax_percentage' => 12])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_tax_percentage)->toBe(12.0);

    Livewire::test(EditTaxGroup::class, ['record' => $record->getKey()])->callAction('delete');
    expect(TaxGroup::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
