<?php

use App\Filament\Resources\GstRates\Pages\CreateGstRate;
use App\Filament\Resources\GstRates\Pages\EditGstRate;
use App\Filament\Resources\GstRates\Pages\ListGstRates;
use App\Models\GstRate;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGstRatesAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from gst rates list', function (): void { get('/admin/gst-rates')->assertRedirect('/admin/login'); });
it('redirects guests away from create gst rate page', function (): void { get('/admin/gst-rates/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a gst rate', function (): void {
    actingAs(makeGstRatesAdminUser());
    Livewire::test(CreateGstRate::class)
        ->fillForm(['cgst_percentage' => null, 'sgst_percentage' => null, 'igst_percentage' => null, 'cess_percentage' => null, 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['cgst_percentage' => 'required', 'sgst_percentage' => 'required', 'igst_percentage' => 'required', 'cess_percentage' => 'required', 'status' => 'required']);
});

it('performs gst rate crud operations', function (): void {
    actingAs(makeGstRatesAdminUser());

    Livewire::test(CreateGstRate::class)
        ->fillForm(['cgst_percentage' => 9, 'sgst_percentage' => 9, 'igst_percentage' => 18, 'cess_percentage' => 0, 'status' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = GstRate::query()->firstOrFail();
    Livewire::test(ListGstRates::class)->assertSuccessful()->assertSee('18');

    Livewire::test(EditGstRate::class, ['record' => $record->getKey()])
        ->fillForm(['cgst_percentage' => 12, 'sgst_percentage' => 12, 'igst_percentage' => 24, 'cess_percentage' => 1, 'status' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditGstRate::class, ['record' => $record->getKey()])->callAction('delete');
});
