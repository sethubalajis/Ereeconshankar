<?php

use App\Filament\Resources\Designs\Pages\CreateDesign;
use App\Filament\Resources\Designs\Pages\EditDesign;
use App\Filament\Resources\Designs\Pages\ListDesigns;
use App\Models\Design;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeDesignsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from designs list', function (): void { get('/admin/designs')->assertRedirect('/admin/login'); });
it('redirects guests away from create design page', function (): void { get('/admin/designs/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a design', function (): void {
    actingAs(makeDesignsAdminUser());
    Livewire::test(CreateDesign::class)
        ->fillForm(['name' => '', 'code' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs design crud operations', function (): void {
    actingAs(makeDesignsAdminUser());

    Livewire::test(CreateDesign::class)
        ->fillForm(['name' => 'Floral', 'code' => 'DSN001', 'description' => 'Floral pattern', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = Design::query()->firstOrFail();
    Livewire::test(ListDesigns::class)->assertSuccessful()->assertSee('Floral');

    Livewire::test(EditDesign::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Geometric', 'code' => 'DSN002', 'description' => 'Geometric pattern', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Geometric');

    Livewire::test(EditDesign::class, ['record' => $record->getKey()])->callAction('delete');
    expect(Design::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
