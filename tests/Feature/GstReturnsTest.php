<?php

use App\Filament\Resources\GstReturns\Pages\CreateGstReturn;
use App\Filament\Resources\GstReturns\Pages\EditGstReturn;
use App\Filament\Resources\GstReturns\Pages\ListGstReturns;
use App\Models\GstReturn;
use App\Models\Role;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

function makeGstReturnsAdminUser(): User
{
    $role = Role::query()->firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);
    return $user;
}

it('redirects guests away from gst returns list', function (): void { get('/admin/gst-returns')->assertRedirect('/admin/login'); });
it('redirects guests away from create gst return page', function (): void { get('/admin/gst-returns/create')->assertRedirect('/admin/login'); });

it('validates required fields when creating a gst return', function (): void {
    actingAs(makeGstReturnsAdminUser());
    Livewire::test(CreateGstReturn::class)
        ->fillForm(['return_type' => '', 'period_start' => null, 'period_end' => null, 'filing_date' => null, 'arn_number' => '', 'status' => null])
        ->call('create')
        ->assertHasFormErrors(['return_type' => 'required', 'period_start' => 'required', 'period_end' => 'required', 'status' => 'required']);
});

it('performs gst return crud operations', function (): void {
    actingAs(makeGstReturnsAdminUser());

    Livewire::test(CreateGstReturn::class)
        ->fillForm(['return_type' => 'GSTR-1', 'period_start' => '2026-07-01', 'period_end' => '2026-07-31', 'filing_date' => '2026-08-10', 'arn_number' => 'ARN001', 'status' => 'pending'])
        ->call('create')
        ->assertHasNoFormErrors();

    $record = GstReturn::query()->firstOrFail();
    Livewire::test(ListGstReturns::class)->assertSuccessful()->assertSee('GSTR-1');

    Livewire::test(EditGstReturn::class, ['record' => $record->getKey()])
        ->fillForm(['return_type' => 'GSTR-3B', 'period_start' => '2026-08-01', 'period_end' => '2026-08-31', 'filing_date' => '2026-09-10', 'arn_number' => 'ARN002', 'status' => 'filed'])
        ->call('save')
        ->assertHasNoFormErrors();

    Livewire::test(EditGstReturn::class, ['record' => $record->getKey()])->callAction('delete');
});
