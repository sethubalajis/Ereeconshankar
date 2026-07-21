<?php

use App\Filament\Resources\FabricTypes\Pages\CreateFabricType;
use App\Filament\Resources\FabricTypes\Pages\EditFabricType;
use App\Filament\Resources\FabricTypes\Pages\ListFabricTypes;
use App\Models\FabricType;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeFabricTypesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from fabric types list', function (): void { get('/admin/fabric-types')->assertRedirect('/admin/login'); });
it('redirects guests away from create fabric type page', function (): void { get('/admin/fabric-types/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a fabric type', function (): void {
    actingAs(makeFabricTypesAdminUser());
    Livewire::test(CreateFabricType::class)
        ->fillForm(['name' => '', 'code' => '', 'description' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'code' => 'required', 'status' => 'required']);
});

it('performs fabric type crud operations', function (): void {
    actingAs(makeFabricTypesAdminUser());

    Livewire::test(CreateFabricType::class)
        ->fillForm(['name' => 'Cotton', 'code' => 'FAB001', 'description' => 'Pure cotton', 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = FabricType::query()->firstOrFail();
    Livewire::test(ListFabricTypes::class)->assertSuccessful()->assertSee('Cotton');

    Livewire::test(EditFabricType::class, ['record' => $record->getKey()])
        ->fillForm(['name' => 'Silk', 'code' => 'FAB002', 'description' => 'Silk fabric', 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    $record->refresh();
    expect($record->name)->toBe('Silk');

    Livewire::test(EditFabricType::class, ['record' => $record->getKey()])->callAction('delete');
    expect(FabricType::query()->whereKey($record->getKey())->exists())->toBeFalse();
});
