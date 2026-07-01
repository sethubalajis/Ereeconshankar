<?php

use App\Filament\Resources\TaxGroups\Pages\CreateTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\EditTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\ListTaxGroups;
use App\Models\Role;
use App\Models\TaxGroup;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeTaxGroupCrudAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    return $user;
}

it('performs tax group crud operations', function (): void {
    actingAs(makeTaxGroupCrudAdminUser());

    Livewire::test(CreateTaxGroup::class)
        ->fillForm([
            'name' => 'GST 5',
            'description' => 'Tax CRUD test',
            'total_tax_percentage' => 5,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = TaxGroup::query()->where('name', 'GST 5')->firstOrFail();

    Livewire::test(ListTaxGroups::class)
        ->assertSuccessful()
        ->assertSee($record->name);

    Livewire::test(EditTaxGroup::class, ['record' => $record->getKey()])
        ->fillForm([
            'name' => 'GST 12',
            'description' => 'Tax CRUD updated',
            'total_tax_percentage' => 12,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect((float) $record->total_tax_percentage)->toBe(12.0);

    Livewire::test(EditTaxGroup::class, ['record' => $record->getKey()])
        ->callAction('delete');

    expect(TaxGroup::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
