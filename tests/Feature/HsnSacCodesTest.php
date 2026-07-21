<?php

use App\Filament\Resources\HsnSacCodes\Pages\CreateHsnSacCode;
use App\Filament\Resources\HsnSacCodes\Pages\EditHsnSacCode;
use App\Filament\Resources\HsnSacCodes\Pages\ListHsnSacCodes;
use App\Models\HsnSacCode;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeHsnSacCodesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from hsn sac codes list', function (): void { get('/admin/hsn-sac-codes')->assertRedirect('/admin/login'); });
it('redirects guests away from create hsn sac code page', function (): void { get('/admin/hsn-sac-codes/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a hsn sac code', function (): void {
    actingAs(makeHsnSacCodesAdminUser());
    Livewire::test(CreateHsnSacCode::class)
        ->fillForm(['code' => '', 'type' => '', 'description' => ''])
        ->call('create')
        ->assertHasFormErrors(['code' => 'required', 'type' => 'required']);
});

it('performs hsn sac code crud operations', function (): void {
    actingAs(makeHsnSacCodesAdminUser());

    Livewire::test(CreateHsnSacCode::class)
        ->fillForm(['code' => '5208', 'type' => 'HSN', 'description' => 'Woven cotton fabric'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = HsnSacCode::query()->firstOrFail();
    Livewire::test(ListHsnSacCodes::class)->assertSuccessful()->assertSee('5208');

    Livewire::test(EditHsnSacCode::class, ['record' => $record->getKey()])
        ->fillForm(['code' => '5209', 'type' => 'SAC', 'description' => 'Updated'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditHsnSacCode::class, ['record' => $record->getKey()])->callAction('delete');
});
